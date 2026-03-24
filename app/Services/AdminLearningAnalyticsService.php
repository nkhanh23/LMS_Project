<?php

namespace App\Services;

use App\Repositories\AdminLearningAnalyticsRepository;

class AdminLearningAnalyticsService
{
    protected $repository;
    public function __construct(AdminLearningAnalyticsRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getCourseCompletionStats(array $filters = [])
    {
        return $this->repository->getCourseCompletionStats($filters);
    }

    public function getUserLearningStats(array $filters = [])
    {
        return $this->repository->getUserLearningStats($filters);
    }

    public function getSummary(array $filters = []): array
    {
        $courseStats = $this->repository->getCourseCompletionStats($filters);
        $userStatsQuery = $this->repository->getUserLearningStats($filters);

        $totalEnrollments = (clone $userStatsQuery)->count();
        $completedEnrollments = (clone $userStatsQuery)
            ->where('completion_percent', 100)
            ->count();

        $avgCompletion = (int) round(
            (clone $userStatsQuery)->avg('completion_percent') ?? 0
        );

        return [
            'total_enrollments' => $totalEnrollments,
            'completed_enrollments' => $completedEnrollments,
            'avg_completion_percent' => $avgCompletion,
            'completion_rate' => $totalEnrollments > 0
                ? (int) round(($completedEnrollments / $totalEnrollments) * 100)
                : 0,
            'course_count' => $courseStats->count(),
        ];
    }
}
