<?php

namespace App\Services;

use App\Repositories\LearnerAssistantRepository;

class LearnerRefundService
{
    public function __construct(
        protected LearnerAssistantRepository $learnerAssistantRepository
    ) {}

    public function getRefundStatus(
        int $userId,
        ?int $courseId = null,
        ?string $orderReference = null
    ): array {
        return [
            'intent' => 'refund_status',
            'source_type' => 'db',
            'resolved_entities' => [
                'course_id' => $courseId,
                'order_reference' => $orderReference,
            ],
            'data' => $this->learnerAssistantRepository->getRefundStatusData($userId, $courseId, $orderReference),
            'data_status' => 'ok',
        ];
    }
}
