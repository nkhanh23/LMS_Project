<?php

namespace App\Repositories;

use App\Models\WebsiteKnowledgeDocument;

class WebsiteKnowledgeRepository
{
    public function searchPublishedDocuments(string $question, ?string $featureName = null, int $limit = 3): array
    {
        $normalizedQuestion = trim($question);
        $terms = $this->extractTerms($normalizedQuestion, $featureName);

        $query = WebsiteKnowledgeDocument::query()
            ->where('status', 'published')
            ->orderByRaw($this->docTypePrioritySql())
            ->orderBy('sort_order')
            ->latest('published_at');

        if (! empty($terms)) {
            $query->where(function ($builder) use ($terms) {
                foreach ($terms as $term) {
                    $builder->orWhere('title', 'like', '%' . $term . '%')
                        ->orWhere('slug', 'like', '%' . $term . '%')
                        ->orWhere('content_markdown', 'like', '%' . $term . '%');
                }
            });
        }

        $documents = $query
            ->limit($limit)
            ->get([
                'id',
                'title',
                'slug',
                'doc_type',
                'excerpt',
                'content_markdown',
                'sort_order',
                'published_at',
            ]);

        return $documents
            ->map(function (WebsiteKnowledgeDocument $document) {
                return [
                    'id' => $document->id,
                    'title' => $document->title,
                    'slug' => $document->slug,
                    'doc_type' => $document->doc_type,
                    'excerpt' => $document->excerpt,
                    'content_markdown' => $document->content_markdown,
                    'published_at' => optional($document->published_at)->toDateTimeString(),
                ];
            })
            ->all();
    }

    private function docTypePrioritySql(): string
    {
        return "CASE doc_type
            WHEN 'feature_how_to' THEN 1
            WHEN 'faq' THEN 2
            WHEN 'policy' THEN 3
            ELSE 99
        END";
    }

    private function extractTerms(string $question, ?string $featureName): array
    {
        $terms = [];

        if ($featureName) {
            $terms[] = $featureName;
        }

        $parts = preg_split('/[\s,?.!;:()]+/u', mb_strtolower($question)) ?: [];
        foreach ($parts as $part) {
            $part = trim($part);
            if (mb_strlen($part) >= 3) {
                $terms[] = $part;
            }
        }

        return array_values(array_unique($terms));
    }
}
