<?php

namespace App\Jobs;

use App\Models\AiDocument;
use App\Models\CourseLecture;
use App\Models\TranscriptJob;
use App\Services\AiDocumentIndexService;
use App\Services\LocalWhisperTranscriptionService;
use App\Services\OpenAiTranscriptionService;
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
    public int $timeout = 3600;
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
        LocalWhisperTranscriptionService $localWhisperTranscriptionService,
        OpenAiTranscriptionService $openAiTranscriptionService
    ): void {
        $job = TranscriptJob::query()->findOrFail($this->transcriptJobId);
        $lecture = CourseLecture::query()->with('course')->findOrFail($job->lecture_id);

        $job->markProcessing(15);

        $transcriptionProvider = config('services.transcription_provider', 'openai');

        if ($transcriptionProvider === 'local_whisper') {
            $result = $localWhisperTranscriptionService->transcribeLecture($lecture);
            $provider = 'local_whisper';
        } else {
            $result = $openAiTranscriptionService->transcribeLecture($lecture);
            $provider = 'openai';
        }
        $metaChunksCount = count($result['segments'] ?? []);

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
        if (class_exists(ProcessAiDocumentJob::class)) {
            ProcessAiDocumentJob::dispatch($document->id)
                ->onConnection(config('services.transcript.queue_connection', 'database'))
                ->onQueue(config('services.transcript.document_queue', 'ai-documents'));
        } else {
            app(AiDocumentIndexService::class)->safeProcess($document);
        }

        $job->markDone(
            documentId: $document->id,
            responsePayload: [
                'provider' => $provider,
                'model' => $result['meta']['model'] ?? config('services.openai_transcription.model', 'gpt-4o-mini-transcribe'),
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
            $job->markFailed($e->getMessage());
        }
    }
}
