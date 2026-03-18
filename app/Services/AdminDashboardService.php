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
            'topCourses' => $this->dashboardRepository->getTopCoursesByRevenue(),
        ];
    }
}
