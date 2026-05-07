<?php

namespace App\Services;

use App\Repositories\AdminDashboardRepository;

class AdminDashboardService
{
    protected $dashboardRepository;

    public function __construct(AdminDashboardRepository $dashboardRepository)
    {
        $this->dashboardRepository = $dashboardRepository;
    }

    public function getDashboardData(): array
    {
        return [
            'summary' => $this->dashboardRepository->getSummary(),
            'trends' => $this->dashboardRepository->getTrendData(),
            'revenueChart' => $this->dashboardRepository->getRevenueChartData(),
            'userGrowthChart' => $this->dashboardRepository->getUserGrowthData(),
            'topCourses' => $this->dashboardRepository->getTopCoursesByRevenue(),
            'topInstructors' => $this->dashboardRepository->getTopInstructors(),
            'alerts' => $this->dashboardRepository->getAlerts(),
            'recentActivity' => $this->dashboardRepository->getRecentActivity(),
            'flowStats' => $this->dashboardRepository->getSystemFlowStats(),
        ];
    }
}
