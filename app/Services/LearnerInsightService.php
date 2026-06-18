<?php

namespace App\Services;

use App\Repositories\LearnerAssistantRepository;

class LearnerInsightService
{
    public function __construct(
        protected LearnerAssistantRepository $learnerAssistantRepository
    ) {}

    public function getCourseProgress(int $userId, int $courseId): array
    {
        $data = $this->learnerAssistantRepository->getCourseProgressData($userId, $courseId);

        return [
            'intent' => 'course_progress',
            'source_type' => 'db',
            'resolved_entities' => [
                'course_id' => $courseId,
            ],
            'data' => $data,
            'data_status' => $data ? 'ok' : 'not_found',
        ];
    }

    public function getUnfinishedCourses(int $userId): array
    {
        return [
            'intent' => 'unfinished_courses',
            'source_type' => 'db',
            'resolved_entities' => [],
            'data' => $this->learnerAssistantRepository->getUnfinishedCoursesData($userId),
            'data_status' => 'ok',
        ];
    }

    public function getQuizHistory(int $userId, ?int $courseId = null, ?int $quizId = null): array
    {
        return [
            'intent' => 'quiz_history',
            'source_type' => 'db',
            'resolved_entities' => [
                'course_id' => $courseId,
                'quiz_id' => $quizId,
            ],
            'data' => $this->learnerAssistantRepository->getQuizHistoryData($userId, $courseId, $quizId),
            'data_status' => 'ok',
        ];
    }

    public function getCertificateStatus(int $userId, ?int $courseId = null): array
    {
        return [
            'intent' => 'certificate_status',
            'source_type' => 'db',
            'resolved_entities' => [
                'course_id' => $courseId,
            ],
            'data' => $this->learnerAssistantRepository->getCertificateStatusData($userId, $courseId),
            'data_status' => 'ok',
        ];
    }
}
