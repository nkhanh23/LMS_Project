<?php

namespace App\Services;

use App\Repositories\InstructorDashboardRepository;

class InstructorDashboardService
{
    protected $dashboardRepository;

    public function __construct(InstructorDashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData(int $instructorId): array
    {
        return [
            'summary' => $this->dashboardRepository->getSummary($instructorId),
            'trends' => $this->dashboardRepository->getTrendData($instructorId),
            'studentChart' => $this->dashboardRepository->getStudentAnalytics($instructorId),
            'revenueChart' => $this->dashboardRepository->getRevenueChart($instructorId),
            'myCourses' => $this->dashboardRepository->getMyCourses($instructorId),
            'recentActivities' => $this->dashboardRepository->getRecentActivities($instructorId),
        ];
    }
}
