<?php

namespace App\Services;

use App\Models\ContentReport;
use App\Models\Course;
use App\Models\CourseReviews;
use App\Models\LectureDiscussion;
use App\Models\User;
use App\Repositories\ContentReportRepository;
use App\Services\InstructorRiskScoreService;
use App\Models\ModerationPolicy;
use App\Models\ModerationActionTemplate;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ModerationService
{
    protected ContentReportRepository $contentReportRepository;
    protected AdminAuditLogService $adminAuditLogService;
    protected InstructorRiskScoreService $instructorRiskScoreService;

    public function __construct(
        ContentReportRepository $contentReportRepository,
        AdminAuditLogService $adminAuditLogService,
        InstructorRiskScoreService $instructorRiskScoreService
    ) {
        $this->contentReportRepository = $contentReportRepository;
        $this->adminAuditLogService = $adminAuditLogService;
        $this->instructorRiskScoreService = $instructorRiskScoreService;
    }

    public function createReviewReport(array $data, CourseReviews $review, int $reporterId): ContentReport
    {
        if ((int) $review->user_id === (int) $reporterId) {
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
            'reporter_id'      => $reporterId,
            'reported_user_id' => $review->user_id,
            'reportable_type'  => 'course_review',
            'reportable_id'    => $review->id,
            'course_id'        => $review->course_id,
            'lecture_id'       => null,
            'reason_code'      => $data['reason_code'],
            'description'      => $data['description'] ?? null,
            'status'           => 'pending',
            'content_snapshot' => [
                'id'            => $review->id,
                'rating'        => $review->rating,
                'comment'       => $review->comment,
                'course_id'     => $review->course_id,
                'user_id'       => $review->user_id,
                'instructor_id' => $review->instructor_id,
                'is_approved'   => (bool) $review->is_approved,
                'created_at'    => $review->created_at,
            ],
        ]);
    }

    public function createDiscussionReport(array $data, LectureDiscussion $discussion, int $reporterId): ContentReport
    {
        if ((int) $discussion->user_id === (int) $reporterId) {
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
            'reporter_id'      => $reporterId,
            'reported_user_id' => $discussion->user_id,
            'reportable_type'  => 'lecture_discussion',
            'reportable_id'    => $discussion->id,
            'course_id'        => $discussion->course_id,
            'lecture_id'       => $discussion->lecture_id,
            'reason_code'      => $data['reason_code'],
            'description'      => $data['description'] ?? null,
            'status'           => 'pending',
            'content_snapshot' => [
                'id'          => $discussion->id,
                'content'     => $discussion->content,
                'course_id'   => $discussion->course_id,
                'lecture_id'  => $discussion->lecture_id,
                'user_id'     => $discussion->user_id,
                'parent_id'   => $discussion->parent_id,
                'is_approved' => (bool) $discussion->is_approved,
                'created_at'  => $discussion->created_at,
            ],
        ]);
    }

    public function resolveReport(ContentReport $report, array $data, ?int $adminId = null): ContentReport
    {
        $adminId = $adminId ?? Auth::id();

        if (!$adminId) {
            throw ValidationException::withMessages([
                'auth' => 'Bạn cần đăng nhập admin để xử lý report.',
            ]);
        }

        return DB::transaction(function () use ($report, $data, $adminId) {
            $report = $report->fresh();

            if (!$report) {
                throw ValidationException::withMessages([
                    'report' => 'Không tìm thấy report.',
                ]);
            }

            if ($report->status !== 'pending') {
                throw ValidationException::withMessages([
                    'report' => 'Report này đã được xử lý trước đó.',
                ]);
            }

            $policyId = $data['policy_id'] ?? null;
            $actionTemplateId = $data['action_template_id'] ?? null;
            $resolutionNote = trim((string) ($data['resolution_note'] ?? ''));

            if (!$policyId) {
                throw ValidationException::withMessages([
                    'policy_id' => 'Vui lòng chọn policy vi phạm.',
                ]);
            }

            if (!$actionTemplateId) {
                throw ValidationException::withMessages([
                    'action_template_id' => 'Vui lòng chọn action xử lý.',
                ]);
            }

            $actionTemplate = ModerationActionTemplate::find($actionTemplateId);
            if (!$actionTemplate) {
                throw ValidationException::withMessages([
                    'action_template_id' => 'Action không hợp lệ.',
                ]);
            }

            $action = $actionTemplate->code;

            if ($resolutionNote === '') {
                $resolutionNote = $actionTemplate->default_note ?? '';
            }

            if ($resolutionNote === '' && $actionTemplate->requires_reason) {
                throw ValidationException::withMessages([
                    'resolution_note' => 'Vui lòng nhập ghi chú xử lý.',
                ]);
            }

            if ($action === 'dismiss') {
                $oldReport = $report->toArray();

                $report->update([
                    'status'             => 'dismissed',
                    'policy_id'          => $policyId,
                    'action_template_id' => $actionTemplateId,
                    'resolution_action'  => 'dismiss',
                    'resolution_note'    => $resolutionNote,
                    'reviewed_by'        => $adminId,
                    'reviewed_at'        => now(),
                ]);

                $this->logAudit(
                    'report_dismissed',
                    'content_report',
                    $report->id,
                    $oldReport,
                    $report->fresh()->toArray(),
                    $resolutionNote,
                    [
                        'reportable_type' => $report->reportable_type,
                        'reportable_id'   => $report->reportable_id,
                        'policy_id'       => $policyId,
                    ]
                );

                return $report->fresh();
            }

            $target = $this->resolveTarget($report);

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
                    $this->lockCourse($report, $resolutionNote, $adminId);
                    break;

                case 'lock_instructor':
                    $this->lockInstructor($report, $resolutionNote, $adminId);
                    break;

                default:
                    throw ValidationException::withMessages([
                        'action' => 'Hành động không hợp lệ.',
                    ]);
            }

            $oldReport = $report->toArray();

            $report->update([
                'status'             => 'resolved',
                'policy_id'          => $policyId,
                'action_template_id' => $actionTemplateId,
                'resolution_action'  => $action,
                'resolution_note'    => $resolutionNote,
                'reviewed_by'        => $adminId,
                'reviewed_at'        => now(),
            ]);

            $this->logAudit(
                'report_resolved',
                'content_report',
                $report->id,
                $oldReport,
                $report->fresh()->toArray(),
                $resolutionNote,
                [
                    'action'             => $action,
                    'policy_id'          => $policyId,
                    'action_template_id' => $actionTemplateId,
                    'reportable_type'    => $report->reportable_type,
                    'reportable_id'      => $report->reportable_id,
                ]
            );

            // Recalculate risk score if the reported user is an instructor
            $reportedUser = User::find($report->reported_user_id);
            if ($reportedUser && $reportedUser->isInstructor()) {
                $this->instructorRiskScoreService->recalculate($reportedUser->id);
            }

            return $report->fresh();
        });
    }

    protected function resolveTarget(ContentReport $report)
    {
        return match ($report->reportable_type) {
            'course_review'      => CourseReviews::withTrashed()->find($report->reportable_id),
            'lecture_discussion' => LectureDiscussion::withTrashed()->find($report->reportable_id),
            default              => null,
        };
    }

    protected function hideTarget(ContentReport $report, $target, string $resolutionNote): void
    {
        $old = $target->toArray();

        $payload = [];

        if (array_key_exists('is_approved', $old)) {
            $payload['is_approved'] = 0;
        }

        if (empty($payload)) {
            throw ValidationException::withMessages([
                'action' => 'Nội dung này không hỗ trợ action ẩn.',
            ]);
        }

        $target->update($payload);

        $this->logAudit(
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

        $this->logAudit(
            $report->reportable_type === 'course_review' ? 'review_deleted' : 'discussion_deleted',
            $report->reportable_type,
            $target->id,
            $old,
            ['deleted' => true],
            $resolutionNote,
            ['report_id' => $report->id]
        );
    }

    protected function lockCourse(ContentReport $report, string $resolutionNote, int $adminId): void
    {
        $course = Course::find($report->course_id);

        if (! $course) {
            throw ValidationException::withMessages([
                'action' => 'Không tìm thấy course để khóa.',
            ]);
        }

        $old = $course->toArray();

        $payload = [];

        if (array_key_exists('approval_status', $old)) {
            $payload['approval_status'] = 'hidden';
        }

        if (array_key_exists('status', $old)) {
            $payload['status'] = 0;
        }

        if (array_key_exists('approval_note', $old)) {
            $payload['approval_note'] = $resolutionNote;
        }

        if (array_key_exists('reviewed_by', $old)) {
            $payload['reviewed_by'] = $adminId;
        }

        if (array_key_exists('reviewed_at', $old)) {
            $payload['reviewed_at'] = now();
        }

        if (empty($payload)) {
            throw ValidationException::withMessages([
                'action' => 'Course hiện chưa có các field cần thiết để khóa.',
            ]);
        }

        $course->update($payload);

        $this->logAudit(
            'course_locked',
            'course',
            $course->id,
            $old,
            $course->fresh()->toArray(),
            $resolutionNote,
            ['report_id' => $report->id]
        );

        // Recalculate risk score for course instructor
        if ($course->instructor_id) {
            $this->instructorRiskScoreService->recalculate($course->instructor_id);
        }
    }

    protected function lockInstructor(ContentReport $report, string $resolutionNote, int $adminId): void
    {
        $user = User::find($report->reported_user_id);

        if (! $user || $user->role !== 'instructor') {
            throw ValidationException::withMessages([
                'action' => 'Nội dung bị report không phải do instructor tạo, không thể khóa instructor.',
            ]);
        }

        $old = $user->toArray();

        $payload = [];

        if (array_key_exists('instructor_approval_status', $old)) {
            $payload['instructor_approval_status'] = 'suspended';
        }

        if (array_key_exists('instructor_review_note', $old)) {
            $payload['instructor_review_note'] = $resolutionNote;
        }

        if (array_key_exists('instructor_reviewed_by', $old)) {
            $payload['instructor_reviewed_by'] = $adminId;
        }

        if (array_key_exists('instructor_reviewed_at', $old)) {
            $payload['instructor_reviewed_at'] = now();
        }

        if (array_key_exists('status', $old)) {
            $payload['status'] = '0';
        }

        if (empty($payload)) {
            throw ValidationException::withMessages([
                'action' => 'User hiện chưa có các field cần thiết để khóa instructor.',
            ]);
        }

        $user->update($payload);

        $this->logAudit(
            'instructor_locked',
            'user',
            $user->id,
            $old,
            $user->fresh()->toArray(),
            $resolutionNote,
            ['report_id' => $report->id]
        );

        // Recalculate risk score for instructor
        $this->instructorRiskScoreService->recalculate($user->id);
    }

    protected function logAudit(
        string $action,
        string $entityType,
        int $entityId,
        array $oldValues,
        array $newValues,
        ?string $note = null,
        array $metadata = []
    ): void {
        if (method_exists($this->adminAuditLogService, 'log')) {
            $this->adminAuditLogService->log(
                $action,
                $entityType,
                $entityId,
                $oldValues,
                $newValues,
                $note,
                $metadata
            );
        }
    }
}
