<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\GovernanceQueueService;

class AdminApprovalCenterController extends Controller
{
    public function index(GovernanceQueueService $governanceQueueService)
    {
        $data = $governanceQueueService->getDashboardData();

        return view('backend.admin.approval-center.index', [
            'stats' => $data['stats'],
            'queueItems' => $data['queueItems'],
        ]);
    }
}
