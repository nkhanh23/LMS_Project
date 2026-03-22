<?php

namespace App\Services;

use App\Repositories\AdminAuditLogRepository;
use Illuminate\Support\Facades\Auth;

class AdminAuditLogService
{
    protected $adminAuditLogRepository;

    public function __construct(AdminAuditLogRepository $adminAuditLogRepository)
    {
        $this->adminAuditLogRepository = $adminAuditLogRepository;
    }

    public function log(
        string $action,
        string $targetType,
        ?int $targetId = null,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?string $note = null,
        ?array $context = null
    ) {
        return $this->adminAuditLogRepository->create([
            'admin_id' => Auth::id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'note' => $note,
            'old_values_json' => $oldValues,
            'new_values_json' => $newValues,
            'context_json' => $context,
            'ip_address' => request()?->ip(),
            'user_agent' => request()?->userAgent(),
        ]);
    }
}
