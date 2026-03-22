<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Repositories\AdminAuditLogRepository;
use Illuminate\Http\Request;

class AdminAuditLogController extends Controller
{
    protected $adminAuditLogRepository;

    public function __construct(AdminAuditLogRepository $adminAuditLogRepository)
    {
        $this->adminAuditLogRepository = $adminAuditLogRepository;
    }

    public function index(Request $request)
    {
        $filters = $request->only([
            'action',
            'target_type',
            'admin_id',
            'from_date',
            'to_date',
        ]);

        $logs = $this->adminAuditLogRepository
            ->getQuery($filters)
            ->paginate(20)
            ->appends($filters);

        return view('backend.admin.audit-log.index', compact('logs', 'filters'));
    }
}
