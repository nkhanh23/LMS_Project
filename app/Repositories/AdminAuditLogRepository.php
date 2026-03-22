<?php

namespace App\Repositories;

use App\Models\AdminAuditLog;

class AdminAuditLogRepository
{
    public function create(array $data): AdminAuditLog
    {
        return AdminAuditLog::create($data);
    }

    public function getQuery(array $filters = [])
    {
        return AdminAuditLog::query()
            ->with('admin:id,name,email')
            ->when($filters['action'] ?? null, function ($query, $action) {
                $query->where('action', $action);
            })
            ->when($filters['target_type'] ?? null, function ($query, $targetType) {
                $query->where('target_type', $targetType);
            })
            ->when($filters['admin_id'] ?? null, function ($query, $adminId) {
                $query->where('admin_id', $adminId);
            })
            ->when($filters['from_date'] ?? null, function ($query, $fromDate) {
                $query->whereDate('created_at', '>=', $fromDate);
            })
            ->when($filters['to_date'] ?? null, function ($query, $toDate) {
                $query->whereDate('created_at', '<=', $toDate);
            })
            ->latest();
    }
}
