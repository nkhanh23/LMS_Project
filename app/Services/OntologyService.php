<?php

namespace App\Services;

use App\Models\AiDocument;
use App\Models\Concept;
use App\Models\CourseLecture;

class OntologyService
{
    public function getLessonConceptIds(int $lectureId): array
    {
        $lecture = CourseLecture::query()
            ->with('concepts:id')
            ->find($lectureId);

        if (! $lecture) {
            return [];
        }

        return $lecture->concepts
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();
    }

    public function getLessonConceptNames(int $lectureId): array
    {
        $lecture = CourseLecture::query()
            ->with('concepts:id,name')
            ->find($lectureId);

        if (! $lecture) {
            return [];
        }

        return $lecture->concepts
            ->pluck('name')
            ->filter()
            ->values()
            ->all();
    }

    public function syncLessonConcepts(CourseLecture $lecture, array $conceptIds): void
    {
        $validIds = Concept::query()
            ->whereIn('id', $conceptIds)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $lecture->concepts()->sync($validIds);
    }

    public function syncDocumentConcepts(AiDocument $document, array $conceptIds): void
    {
        $validIds = Concept::query()
            ->whereIn('id', $conceptIds)
            ->pluck('id')
            ->map(fn($id) => (int) $id)
            ->values()
            ->all();

        $document->concepts()->sync($validIds);
    }
}
