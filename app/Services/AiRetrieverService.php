<?php

namespace App\Services;

use App\Models\AiDocumentChunk;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class AiRetrieverService
{
    public function retrieve(string $question, int $courseId, int $lectureId, int $limit = 5): Collection
    {
        $lessonChunks = $this->searchChunks(
            question: $question,
            courseId: $courseId,
            lectureId: $lectureId,
            limit: $limit
        );

        if ($lessonChunks->count() >= 3) {
            return $lessonChunks;
        }

        $courseChunks = $this->searchChunks(
            question: $question,
            courseId: $courseId,
            lectureId: null,
            limit: $limit
        );

        return $lessonChunks
            ->concat($courseChunks)
            ->unique('id')
            ->take($limit)
            ->values();
    }

    protected function searchChunks(string $question, int $courseId, ?int $lectureId, int $limit): Collection
    {
        $safeQuestion = trim($question);
        $like = '%' . str_replace(' ', '%', $safeQuestion) . '%';

        $query = AiDocumentChunk::query()
            ->select('ai_document_chunks.*')
            ->selectRaw("
                (
                    CASE 
                        WHEN content ILIKE ? THEN 2
                        ELSE 0
                    END
                    +
                    ts_rank(
                        to_tsvector('simple', content),
                        plainto_tsquery('simple', ?)
                    )
                ) as relevance_score
            ", [$like, $safeQuestion])
            ->with('document')
            ->where('course_id', $courseId);

        if ($lectureId !== null) {
            $query->where('lecture_id', $lectureId);
        }

        return $query
            ->orderByDesc('relevance_score')
            ->orderBy('id')
            ->limit($limit)
            ->get();
    }
}
