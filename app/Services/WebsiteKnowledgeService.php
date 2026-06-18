<?php

namespace App\Services;

use App\Repositories\WebsiteKnowledgeRepository;

class WebsiteKnowledgeService
{
    public function __construct(
        protected WebsiteKnowledgeRepository $websiteKnowledgeRepository
    ) {}

    public function getFeatureHowTo(string $question, ?string $featureName = null): array
    {
        $documents = $this->websiteKnowledgeRepository->searchPublishedDocuments(
            question: $question,
            featureName: $featureName,
            limit: 3
        );

        if (empty($documents)) {
            return [
                'intent' => 'feature_how_to',
                'source_type' => 'kb',
                'data_status' => 'not_found',
                'data' => [
                    'documents' => [],
                    'matched_feature' => $featureName,
                ],
            ];
        }

        return [
            'intent' => 'feature_how_to',
            'source_type' => 'kb',
            'data_status' => 'ok',
            'data' => [
                'primary_document' => $documents[0],
                'documents' => $documents,
                'matched_feature' => $featureName,
                'doc_priority_rule' => [
                    'feature_how_to',
                    'faq',
                    'policy',
                ],
            ],
        ];
    }
}
