<?php

namespace App\Repositories;

use App\Models\ContentReport;
use App\Models\Course;
use App\Models\InstructorRequest;
use App\Models\RefundRequest;

class GovernanceQueueRepository
{
    public function getQueueStats(): array
    {
        return [
            'pending_instructor_requests' => InstructorRequest::where('status', 'pending')->count(),
            'pending_course_approvals'    => Course::where('approval_status', 'pending_review')->count(),
            'pending_reports'             => ContentReport::where('status', 'pending')->count(),
            'pending_refunds'             => RefundRequest::where('status', 'pending')->count(),
        ];
    }

    public function getUnifiedQueue(int $limit = 10)
    {
        $instructorRequests = InstructorRequest::latest()
            ->where('status', 'pending')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'instructor_request',
                    'id' => $item->id,
                    'title' => 'Instructor request pending',
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                    'url' => route('admin.instructor-requests.index'),
                ];
            });

        $courseApprovals = Course::latest()
            ->where('approval_status', 'pending_review')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'course_approval',
                    'id' => $item->id,
                    'title' => 'Course approval pending',
                    'status' => 'pending',
                    'created_at' => $item->created_at,
                    'url' => route('admin.course-approvals.index'),
                ];
            });

        $reports = ContentReport::latest()
            ->where('status', 'pending')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'content_report',
                    'id' => $item->id,
                    'title' => 'Content report pending',
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                    'url' => route('admin.moderation.reports.show', $item->id),
                ];
            });

        $refunds = RefundRequest::latest()
            ->where('status', 'pending')
            ->take($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'refund_request',
                    'id' => $item->id,
                    'title' => 'Refund request pending',
                    'status' => $item->status,
                    'created_at' => $item->created_at,
                    'url' => route('admin.orders.refund_requests.index'),
                ];
            });

        return collect()
            ->merge($instructorRequests)
            ->merge($courseApprovals)
            ->merge($reports)
            ->merge($refunds)
            ->sortByDesc('created_at')
            ->values();
    }
}
