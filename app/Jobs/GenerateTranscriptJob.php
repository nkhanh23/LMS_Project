<?php

namespace App\Jobs;

use App\Models\AiDocument;
use App\Models\CourseLecture;
use App\Models\TranscriptJob;
use App\Services\OpenAiTranscriptionService;
use App\Services\YoutubeTranscriptService;
use Illuminate\Support\Str;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Throwable;

class GenerateTranscriptJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public int $timeout = 600;
    /**
     * Create a new job instance.
     */
    public function __construct(public int $transcriptJobId)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(
        OpenAiTranscriptionService $openAiTranscriptionService,
        YoutubeTranscriptService $youtubeTranscriptService
    ): void {
        $job = TranscriptJob::query()->findOrFail($this->transcriptJobId); //
        $lecture = CourseLecture::query()->with('course')->findOrFail($job->lecture_id); //

        $job->markProcessing(15); //

        // Kiểm tra xem URL có phải là của YouTube không
        $isYoutube = Str::contains($lecture->url, ['youtube.com', 'youtu.be']);

        if ($isYoutube) {
            // Lấy trực tiếp từ YouTube
            $result = $youtubeTranscriptService->fetchTranscript($lecture);
            $provider = 'youtube_captions';
            $metaChunksCount = 1;
        } else {
            // Chạy pipeline OpenAI hiện tại
            $result = $openAiTranscriptionService->transcribeLecture($lecture); //
            $provider = 'openai';
            $metaChunksCount = count($result['segments'] ?? []);
        }

        $job->markProcessing(70);

        // Tạo Document từ text trích xuất được
        $document = AiDocument::query()->create([
            'course_id' => $lecture->course_id,
            'lecture_id' => $lecture->id,
            'uploaded_by' => $job->requested_by,
            'title' => 'Transcript - ' . ($lecture->lecture_title ?: ('Lecture #' . $lecture->id)),
            'source_type' => 'transcript',
            'file_name' => ($lecture->file_name ?: 'lecture_' . $lecture->id) . '_transcript.txt',
            'mime_type' => 'text/plain',
            'storage_disk' => null,
            'storage_path' => null,
            'extracted_text' => $result['cleaned_text'],
            'language' => $result['language'] ?: 'vi',
            'index_status' => 'pending',
            'index_error' => null,
            'indexed_at' => null,
        ]);

        $job->markProcessing(85);

        // Gọi Job xử lý Chunk & Embedding
        if (class_exists(\App\Jobs\ProcessAiDocumentJob::class)) {
            \App\Jobs\ProcessAiDocumentJob::dispatch($document->id);
        } else {
            app(\App\Services\AiDocumentIndexService::class)->safeProcess($document);
        }

        $job->markDone(
            documentId: $document->id,
            responsePayload: [
                'provider' => $provider,
                'model' => $isYoutube ? 'youtube_auto_caption' : config('services.openai_transcription.model', 'gpt-4o-mini-transcribe'),
                'segments_count' => $metaChunksCount,
                'language' => $result['language'] ?? 'vi',
                'meta' => $result['meta'] ?? [],
            ]
        );
    }

    public function failed(Throwable $e): void
    {
        $job = TranscriptJob::query()->find($this->transcriptJobId);
        if ($job) {
            // Exception từ YoutubeTranscriptService sẽ được catch ở đây và update vào db
            $job->markFailed($e->getMessage());
        }
    }
}
