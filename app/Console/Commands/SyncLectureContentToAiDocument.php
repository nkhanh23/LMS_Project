<?php

namespace App\Console\Commands;

use App\Models\AiDocument;
use App\Models\CourseLecture;
use App\Services\AiDocumentIndexService;
use Illuminate\Console\Command;

class SyncLectureContentToAiDocument extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ai:sync-lecture-content';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Đồng bộ course_lectures.content sang ai_documents';

    /**
     * Execute the console command.
     */
    public function handle(AiDocumentIndexService $indexService): int
    {
        $lectures = CourseLecture::query()
            ->whereNotNull('content')
            ->where('content', '!=', '')
            ->get();

        foreach ($lectures as $lecture) {
            $document = AiDocument::query()->updateOrCreate(
                [
                    'course_id' => $lecture->course_id,
                    'lecture_id' => $lecture->id,
                    'source_type' => 'lesson_content',
                    'title' => 'Lesson Content - ' . ($lecture->lecture_title ?? $lecture->id),
                ],
                [
                    'uploaded_by' => 1,
                    'extracted_text' => $lecture->content,
                    'language' => 'vi',
                    'index_status' => 'pending',
                ]
            );

            $indexService->safeReindex($document);
        }

        $this->info('Đồng bộ lesson content thành công.');

        return self::SUCCESS;
    }
}
