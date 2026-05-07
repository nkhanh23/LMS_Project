<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\SystemHealthService;

class AdminSystemHealthController extends Controller
{
    protected $healthService;

    public function __construct(SystemHealthService $healthService)
    {
        $this->healthService = $healthService;
    }

    public function index()
    {
        $healthData = $this->healthService->getDashboardData();
        return view('backend.admin.system-health.index', compact('healthData'));
    }
}
