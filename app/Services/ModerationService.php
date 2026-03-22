<?php

namespace App\Services;

use App\Models\ContentReport;
use App\Models\Course;
use App\Models\CourseReviews;
use App\Models\LectureDiscussion;
use App\Models\User;
use App\Repositories\ContentReportRepository;
use App\Services\AdminAuditLogService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ModerationService
{
    protected $contentReportRepository;
    protected $adminAuditLogService;

    public function __construct(ContentReportRepository $contentReportRepository, AdminAuditLogService $adminAuditLogService)
    {
        $this->contentReportRepository = $contentReportRepository;
        $this->adminAuditLogService = $adminAuditLogService;
    }

    public function createReviewReport(array $data, CourseReviews $review, int $reporterId): ContentReport
    {
        if ($review->user_id === $reporterId) {
            throw ValidationException::withMessages([
                'report' => 'Bạn không thể tự báo cáo review của chính mình.',
            ]);
        }

        $duplicate = $this->contentReportRepository->findOpenDuplicate(
            $reporterId,
            'course_review',
            $review->id
        );

        if ($duplicate) {
            throw ValidationException::withMessages([
                'report' => 'Bạn đã báo cáo review này rồi và đang chờ xử lý.',
            ]);
        }

        return $this->contentReportRepository->create([
            'reporter_id' => $reporterId,
            'reported_user_id' => $review->user_id,
            'reportable_type' => 'course_review',
            'reportable_id' => $review->id,
            'course_id' => $review->course_id,
            'lecture_id' => null,
            'reason_code' => $data['reason_code'],
            'description' => $data['description'] ?? null,
            'status' => 'pending',
            'content_snapshot' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'course_id' => $review->course_id,
                'user_id' => $review->user_id,
                'instructor_id' => $review->instructor_id,
                'is_approved' => (bool) $review->is_approved,
                'created_at' => $review->created_at,
            ],
        ]);
    }

    public function createDiscussionReport(array $data, LectureDiscussion $discussion, int $reporterId): ContentReport
    {
        if ($discussion->user_id === $reporterId) {
            throw ValidationException::withMessages([
                'report' => 'Bạn không thể tự báo cáo thảo luận của chính mình.',
            ]);
        }

        $duplicate = $this->contentReportRepository->findOpenDuplicate(
            $reporterId,
            'lecture_discussion',
            $discussion->id
        );

        if ($duplicate) {
            throw ValidationException::withMessages([
                'report' => 'Bạn đã báo cáo thảo luận này rồi và đang chờ xử lý.',
            ]);
        }

        return $this->contentReportRepository->create([
            'reporter_id' => $reporterId,
            'reported_user_id' => $discussion->user_id,
            'reportable_type' => 'lecture_discussion',
            'reportable_id' => $discussion->id,
            'course_id' => $discussion->course_id,
            'lecture_id' => $discussion->lecture_id,
            'reason_code' => $data['reason_code'],
            'description' => $data['description'] ?? null,
            'status' => 'pending',
            'content_snapshot' => [
                'id' => $discussion->id,
                'content' => $discussion->content,
                'course_id' => $discussion->course_id,
                'lecture_id' => $discussion->lecture_id,
                'user_id' => $discussion->user_id,
                'parent_id' => $discussion->parent_id,
                'is_approved' => (bool) $discussion->is_approved,
                'created_at' => $discussion->created_at,
            ],
        ]);
    }

    public function resolveReport(ContentReport $report, array $data): ContentReport
    {
        return DB::transaction(function () use ($report, $data) {
            $target = $this->resolveTarget($report);
            $action = $data['action'];
            $resolutionNote = $data['resolution_note'];

            if ($action === 'dismiss') {
                $oldReport = $report->toArray();

                $report->update([
                    'status' => 'dismissed',
                    'resolution_action' => 'dismiss',
                    'resolution_note' => $resolutionNote,
                    'reviewed_by' => Auth::id(),
                    'reviewed_at' => now(),
                ]);

                $this->adminAuditLogService->log(
                    'report_dismissed',
                    'content_report',
                    $report->id,
                    $oldReport,
                    $report->fresh()->toArray(),
                    $resolutionNote,
                    ['reportable_type' => $report->reportable_type, 'reportable_id' => $report->reportable_id]
                );

                return $report->fresh();
            }

            if (!$target) {
                throw ValidationException::withMessages([
                    'action' => 'Không tìm thấy nội dung gốc để xử lý.',
                ]);
            }

            switch ($action) {
                case 'hide_content':
                    $this->hideTarget($report, $target, $resolutionNote);
                    break;

                case 'delete_content':
                    $this->deleteTarget($report, $target, $resolutionNote);
                    break;

                case 'lock_course':
                    $this->lockCourse($report, $resolutionNote);
                    break;

                case 'lock_instructor':
                    $this->lockInstructor($report, $resolutionNote);
                    break;

                default:
                    throw ValidationException::withMessages([
                        'action' => 'Hành động không hợp lệ.',
                    ]);
            }

            $oldReport = $report->toArray();

            $report->update([
                'status' => 'resolved',
                'resolution_action' => $action,
                'resolution_note' => $resolutionNote,
                'reviewed_by' => Auth::id(),
                'reviewed_at' => now(),
            ]);

            $this->adminAuditLogService->log(
                'report_resolved',
                'content_report',
                $report->id,
                $oldReport,
                $report->fresh()->toArray(),
                $resolutionNote,
                ['action' => $action, 'reportable_type' => $report->reportable_type, 'reportable_id' => $report->reportable_id]
            );

            return $report->fresh();
        });
    }

    protected function resolveTarget(ContentReport $report)
    {
        return match ($report->reportable_type) {
            'course_review' => CourseReviews::withTrashed()->find($report->reportable_id),
            'lecture_discussion' => LectureDiscussion::withTrashed()->find($report->reportable_id),
            default => null,
        };
    }

    protected function hideTarget(ContentReport $report, $target, string $resolutionNote): void
    {
        $old = $target->toArray();

        $target->update([
            'is_approved' => 0,
        ]);

        $this->adminAuditLogService->log(
            $report->reportable_type === 'course_review' ? 'review_hidden' : 'discussion_hidden',
            $report->reportable_type,
            $target->id,
            $old,
            $target->fresh()->toArray(),
            $resolutionNote,
            ['report_id' => $report->id]
        );
    }

    protected function deleteTarget(ContentReport $report, $target, string $resolutionNote): void
    {
        $old = $target->toArray();
        $target->delete();

        $this->adminAuditLogService->log(
            $report->reportable_type === 'course_review' ? 'review_deleted' : 'discussion_deleted',
            $report->reportable_type,
            $target->id,
            $old,
            ['deleted' => true],
            $resolutionNote,
            ['report_id' => $report->id]
        );
    }

    protected function lockCourse(ContentReport $report, string $resolutionNote): void
    {
        $course = Course::find($report->course_id);

        if (!$course) {
            throw ValidationException::withMessages([
                'action' => 'Không tìm thấy course để khóa.',
            ]);
        }

        $old = $course->toArray();

        $course->update([
            'approval_status' => 'hidden',
            'status' => 0,
            'approval_note' => $resolutionNote,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->adminAuditLogService->log(
            'course_locked',
            'course',
            $course->id,
            $old,
            $course->fresh()->toArray(),
            $resolutionNote,
            ['report_id' => $report->id]
        );
    }

    protected function lockInstructor(ContentReport $report, string $resolutionNote): void
    {
        $user = User::find($report->reported_user_id);

        if (!$user || $user->role !== 'instructor') {
            throw ValidationException::withMessages([
                'action' => 'Nội dung bị report không phải do instructor tạo, không thể khóa instructor.',
            ]);
        }

        $old = $user->toArray();

        $user->update([
            'instructor_approval_status' => 'suspended',
            'instructor_review_note' => $resolutionNote,
            'instructor_reviewed_by' => Auth::id(),
            'instructor_reviewed_at' => now(),
            'status' => '0',
        ]);

        $this->adminAuditLogService->log(
            'instructor_locked',
            'user',
            $user->id,
            $old,
            $user->fresh()->toArray(),
            $resolutionNote,
            ['report_id' => $report->id]
        );
    }
}
