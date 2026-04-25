<?php

namespace App\Services;

use App\Models\AiDocumentChunk;
use App\Services\OntologyService;
use Illuminate\Support\Collection;

class AiRetrieverService
{

    public function __construct(
        protected AiEmbeddingService $embeddingService,
        protected OntologyService $ontologyService
    ) {}
    public function retrieve(
        string $question,
        int $courseId,
        ?int $lectureId,
        int $limit = 5
    ): array {
        $lessonConceptIds = $lectureId
            ? $this->ontologyService->getLessonConceptIds($lectureId)
            : [];

        $lessonVectorChunks = $this->applyConceptBoost(
            chunks: $this->searchByVector(
                question: $question,
                courseId: $courseId,
                lectureId: $lectureId,
                limit: $this->expandedLimit($limit)
            ),
            lessonConceptIds: $lessonConceptIds
        )->take($limit)->values();

        if ($lessonVectorChunks->count() >= 3) {
            return $this->buildResult(
                chunks: $lessonVectorChunks,
                sourceScope: 'lesson',
                lessonConceptIds: $lessonConceptIds
            );
        }

        $lessonKeywordChunks = $this->applyConceptBoost(
            chunks: $this->searchByKeyword(
                question: $question,
                courseId: $courseId,
                lectureId: $lectureId,
                limit: $this->expandedLimit($limit)
            ),
            lessonConceptIds: $lessonConceptIds
        );

        $courseVectorChunks = $this->applyConceptBoost(
            chunks: $this->searchByVector(
                question: $question,
                courseId: $courseId,
                lectureId: null,
                limit: $this->expandedLimit($limit)
            ),
            lessonConceptIds: $lessonConceptIds
        );

        $merged = $lessonVectorChunks
            ->concat($lessonKeywordChunks)
            ->concat($courseVectorChunks)
            ->unique('id')
            ->sortByDesc(fn($chunk) => (float) ($chunk->relevance_score ?? 0))
            ->take($limit)
            ->values();

        $sourceScope = match (true) {
            $lectureId !== null && $merged->where('lecture_id', $lectureId)->isNotEmpty() && $merged->where('lecture_id', null)->isEmpty() => 'lesson',
            $lectureId !== null && $merged->where('lecture_id', $lectureId)->isNotEmpty() => 'lesson+course',
            $merged->isNotEmpty() => 'course',
            default => 'none',
        };

        return $this->buildResult(
            chunks: $merged,
            sourceScope: $sourceScope,
            lessonConceptIds: $lessonConceptIds
        );
    }

    protected function searchByVector(
        string $question,
        int $courseId,
        ?int $lectureId,
        int $limit
    ): Collection {
        try {
            $embedding = $this->embeddingService->embedQuery($question);
            $vectorLiteral = $this->vectorLiteral($embedding['values']);

            $query = AiDocumentChunk::query()
                ->select('ai_document_chunks.*')
                ->selectRaw("1 - (embedding <=> ?::vector) AS relevance_score", [$vectorLiteral])
                ->with(['document', 'document.concepts'])
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

    protected function searchByKeyword(
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
                "(CASE WHEN ai_document_chunks.content ILIKE ? THEN 2 ELSE 0 END +
                ts_rank(
                    to_tsvector('simple', coalesce(ai_document_chunks.content, '')),
                    plainto_tsquery('simple', ?)
                )) as relevance_score",
                [$like, $safeQuestion]
            )
            ->with(['document', 'document.concepts'])
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

    protected function applyConceptBoost(Collection $chunks, array $lessonConceptIds): Collection
    {
        if ($chunks->isEmpty()) {
            return collect();
        }

        return $chunks
            ->map(function ($chunk) use ($lessonConceptIds) {
                $documentConceptIds = collect($chunk->document?->concepts ?? [])
                    ->pluck('id')
                    ->map(fn($id) => (int) $id)
                    ->values()
                    ->all();

                $matchedConceptIds = array_values(array_intersect($lessonConceptIds, $documentConceptIds));
                $matchCount = count($matchedConceptIds);

                // boost nhẹ: tối đa +0.15
                $boost = min(0.15, $matchCount * 0.05);

                $chunk->concept_match_count = $matchCount;
                $chunk->matched_concept_ids = $matchedConceptIds;
                $chunk->relevance_score = (float) ($chunk->relevance_score ?? 0) + $boost;

                return $chunk;
            })
            ->sortByDesc(fn($chunk) => (float) ($chunk->relevance_score ?? 0))
            ->values();
    }

    protected function buildResult(
        Collection $chunks,
        string $sourceScope,
        array $lessonConceptIds = []
    ): array {
        return [
            'chunks' => $chunks,
            'source_scope' => $sourceScope,
            'evidence_strength' => $this->determineEvidenceStrength($chunks),
            'lesson_concept_ids' => $lessonConceptIds,
        ];
    }

    protected function determineEvidenceStrength(Collection $chunks): string
    {
        if ($chunks->isEmpty()) {
            return 'none';
        }

        $positiveScoreChunks = $chunks->filter(
            fn($chunk) => (float) ($chunk->relevance_score ?? 0) > 0.2
        );

        if ($positiveScoreChunks->count() >= 2) {
            return 'enough';
        }

        return 'weak';
    }

    protected function expandedLimit(int $limit): int
    {
        return max(12, $limit * 4);
    }

    protected function vectorLiteral(array $values): string
    {
        return '[' . implode(',', array_map(
            fn($v) => is_float($v) || is_int($v) ? (string) $v : (string) ((float) $v),
            $values
        )) . ']';
    }
}
