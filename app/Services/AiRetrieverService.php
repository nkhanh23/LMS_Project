<?php

namespace App\Services;

use App\Models\AiDocumentChunk;
use Illuminate\Support\Collection;

class AiRetrieverService
{

    public function __construct(
        protected AiEmbeddingService $embeddingService
    ) {}
    public function retrieve(
        string $question,
        int $courseId,
        ?int $lectureId,
        int $limit = 5
    ): array {
        $lessonVectorChunks = $this->searchByVector(
            question: $question,
            courseId: $courseId,
            lectureId: $lectureId,
            limit: $limit
        );

        if ($lessonVectorChunks->count() >= 3) {
            return $this->buildResult($lessonVectorChunks, 'lesson');
        }

        $lessonKeywordChunks = $this->searchByKeyword($question, $courseId, $lectureId, $limit);

        $courseVectorChunks = $this->searchByVector(
            question: $question,
            courseId: $courseId,
            lectureId: null,
            limit: $limit
        );

        $merged = $lessonVectorChunks
            ->concat($lessonKeywordChunks)
            ->concat($courseVectorChunks)
            ->unique('id')
            ->take($limit)
            ->values();

        $sourceScope = match (true) {
            $lectureId !== null && $merged->where('lecture_id', $lectureId)->isNotEmpty() && $merged->where('lecture_id', null)->isEmpty() => 'lesson',
            $lectureId !== null && $merged->where('lecture_id', $lectureId)->isNotEmpty() => 'lesson+course',
            $merged->isNotEmpty() => 'course',
            default => 'none',
        };

        return $this->buildResult($merged, $sourceScope);
    }

    protected function searchByVector(string $question, int $courseId, ?int $lectureId, int $limit): Collection
    {
        try {
            $embedding = $this->embeddingService->embedQuery($question);
            $vectorLiteral = $this->vectorLiteral($embedding['values']);

            $query = AiDocumentChunk::query()
                ->select('ai_document_chunks.*')
                ->selectRaw("1 - (embedding <=> ?::vector) AS relevance_score", [$vectorLiteral])
                ->with('document')
                ->where('course_id', $courseId)
                ->whereNotNull('embedding');

            if ($lectureId !== null) {
                $query->where('lecture_id', $lectureId);
            }

            return $query
                ->orderByDesc('relevance_score')
                ->limit($limit)
                ->get();
        } catch (\Throwable $e) {
            report($e);

            return collect();
        }
    }

    protected function searchByKeyword(string $question, int $courseId, ?int $lectureId, int $limit): Collection
    {
        $safeQuestion = trim($question);

        if ($safeQuestion === '') {
            return collect();
        }

        $like = '%' . str_replace(' ', '%', $safeQuestion) . '%';

        $query = AiDocumentChunk::query()
            ->select('ai_document_chunks.*')
            ->selectRaw(
                "(CASE WHEN ai_document_chunks.content ILIKE ? THEN 2 ELSE 0 END +
                ts_rank(
                    to_tsvector('simple', coalesce(ai_document_chunks.content, '')),
                    plainto_tsquery('simple', ?)
                )) as relevance_score",
                [$like, $safeQuestion]
            )
            ->with('document')
            ->where('ai_document_chunks.course_id', $courseId)
            ->where(function ($q) use ($like, $safeQuestion) {
                $q->where('ai_document_chunks.content', 'ILIKE', $like)
                    ->orWhereRaw(
                        "to_tsvector('simple', coalesce(ai_document_chunks.content, '')) @@ plainto_tsquery('simple', ?)",
                        [$safeQuestion]
                    );
            });

        if ($lectureId !== null) {
            $query->where('ai_document_chunks.lecture_id', $lectureId);
        }

        return $query
            ->orderByDesc('relevance_score')
            ->limit($limit)
            ->get();
    }

    protected function buildResult(Collection $chunks, string $sourceScope): array
    {
        return [
            'chunks' => $chunks,
            'source_scope' => $sourceScope,
            'evidence_strength' => $this->determineEvidenceStrength($chunks),
        ];
    }

    protected function determineEvidenceStrength(Collection $chunks): string
    {
        if ($chunks->isEmpty()) {
            return 'none';
        }

        $positiveScoreChunks = $chunks->filter(fn($chunk) => (float) ($chunk->relevance_score ?? 0) > 0.2);

        if ($positiveScoreChunks->count() >= 2) {
            return 'enough';
        }

        return 'weak';
    }

    protected function searchChunks(
        string $question,
        int $courseId,
        ?int $lectureId,
        int $limit
    ): Collection {
        $safeQuestion = trim($question);

        if ($safeQuestion === '') {
            return collect();
        }

        $like = '%' . str_replace(' ', '%', $safeQuestion) . '%';

        $query = AiDocumentChunk::query()
            ->select('ai_document_chunks.*')
            ->selectRaw(
                "
                (
                    CASE
                        WHEN ai_document_chunks.content ILIKE ? THEN 2
                        ELSE 0
                    END
                    +
                    ts_rank(
                        to_tsvector('simple', coalesce(ai_document_chunks.content, '')),
                        plainto_tsquery('simple', ?)
                    )
                ) as relevance_score
                ",
                [$like, $safeQuestion]
            )
            ->with('document')
            ->where('ai_document_chunks.course_id', $courseId)
            ->where(function ($q) use ($like, $safeQuestion) {
                $q->where('ai_document_chunks.content', 'ILIKE', $like)
                    ->orWhereRaw(
                        "to_tsvector('simple', coalesce(ai_document_chunks.content, '')) @@ plainto_tsquery('simple', ?)",
                        [$safeQuestion]
                    );
            });

        if ($lectureId !== null) {
            $query->where('ai_document_chunks.lecture_id', $lectureId);
        }

        return $query
            ->orderByDesc('relevance_score')
            ->orderBy('ai_document_chunks.id')
            ->limit($limit)
            ->get();
    }

    protected function vectorLiteral(array $values): string
    {
        return '[' . implode(',', array_map(
            fn($v) => is_float($v) || is_int($v) ? (string) $v : (string) ((float) $v),
            $values
        )) . ']';
    }
}
