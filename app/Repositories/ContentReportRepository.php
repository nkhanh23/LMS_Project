<?php

namespace App\Repositories;

use App\Models\ContentReport;

class ContentReportRepository
{
    public function create(array $data): ContentReport
    {
        return ContentReport::create($data);
    }

    public function findById(int $id): ?ContentReport
    {
        return ContentReport::with([
            'reporter:id,name,email',
            'reportedUser:id,name,email,role',
            'reviewer:id,name,email',
            'course:id,course_name,instructor_id',
            'lecture:id,lecture_title,course_id',
        ])->find($id);
    }

    public function findOpenDuplicate(int $reporterId, string $reportableType, int $reportableId): ?ContentReport
    {
        return ContentReport::query()
            ->where('reporter_id', $reporterId)
            ->where('reportable_type', $reportableType)
            ->where('reportable_id', $reportableId)
            ->whereIn('status', ['pending', 'reviewing'])
            ->first();
    }

    public function getQuery(array $filters = [])
    {
        return ContentReport::query()
            ->with([
                'reporter:id,name,email',
                'reportedUser:id,name,email,role',
                'reviewer:id,name,email',
                'course:id,course_name,instructor_id',
                'lecture:id,lecture_title,course_id',
            ])
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['reportable_type'] ?? null, function ($query, $reportableType) {
                $query->where('reportable_type', $reportableType);
            })
            ->when($filters['reason_code'] ?? null, function ($query, $reasonCode) {
                $query->where('reason_code', $reasonCode);
            })
            ->when($filters['course_id'] ?? null, function ($query, $courseId) {
                $query->where('course_id', $courseId);
            })
            ->latest();
    }
}
