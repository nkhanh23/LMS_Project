<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseQualityCheck;
use App\Services\AdminAuditLogService;
use App\Services\InstructorRiskScoreService;
use App\Services\CourseQualityChecklistService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminCourseApprovalController extends Controller
{
    protected $adminAuditLogService;
    protected $instructorRiskScoreService;

    public function __construct(AdminAuditLogService $adminAuditLogService, InstructorRiskScoreService $instructorRiskScoreService)
    {
        $this->adminAuditLogService = $adminAuditLogService;
        $this->instructorRiskScoreService = $instructorRiskScoreService;
    }
    public function index(Request $request)
    {
        $courseStatus = $request->input('course_status');

        $courses = Course::query()
            ->with(['user.riskScore', 'category'])
            ->when($courseStatus, function ($query) use ($courseStatus) {
                $query->where('approval_status', $courseStatus);
            })
            ->latest()
            ->paginate(10);

        return view('backend.admin.course-approval.index', compact('courses', 'courseStatus'));
    }

    public function approve(Course $course, CourseQualityCheck $checklistService, AdminAuditLogService $auditLogService)
    {
        $checks = $checklistService->sync($course, Auth::id());

        $canApprove = collect($checks)->every(fn($item) => $item['status'] === 'pass');

        if (! $canApprove) {
            return redirect()->back()->with('error', 'Khóa học chưa đạt quality checklist để publish');
        }

        $course->update([
            'approval_status' => 'approved',
        ]);

        $auditLogService->log(
            'course_approved',
            'course',
            $course->id,
            null,
            null,
            'Khóa học đã được duyệt'
        );

        return redirect()->back()->with('success', 'Khóa học đã được duyệt');
    }

    public function publish($id)
    {
        $course = Course::findOrFail($id);
        $old = $course->only([
            'approval_status',
            'approval_note',
            'status',
            'approved_at',
            'reviewed_by',
            'reviewed_at',
        ]);
        $course->update([
            'approval_status' => 'published',
            'approval_note' => null,
            'status' => 1,
            'approved_at' => now(),
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->adminAuditLogService->log(
            'course_published',
            'course',
            $course->id,
            $old,
            $course->fresh()->only([
                'approval_status',
                'approval_note',
                'status',
                'approved_at',
                'reviewed_by',
                'reviewed_at',
            ]),
            null,
            ['source' => 'admin_course_approval']
        );

        return back()->with('success', 'Khóa học đã được publish.');
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'review_note' => 'required|string|max:5000',
        ]);

        $course = Course::findOrFail($id);
        $old = $course->only([
            'approval_status',
            'approval_note',
            'status',
            'reviewed_by',
            'reviewed_at',
        ]);
        $course->update([
            'approval_status' => 'rejected',
            'approval_note' => $validated['review_note'],
            'status' => 0,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->adminAuditLogService->log(
            'course_rejected',
            'course',
            $course->id,
            $old,
            $course->fresh()->only([
                'approval_status',
                'approval_note',
                'status',
                'reviewed_by',
                'reviewed_at',
            ]),
            $validated['review_note'],
            ['source' => 'admin_course_approval']
        );

        if ($course->instructor_id) {
            $this->instructorRiskScoreService->recalculate($course->instructor_id);
        }

        return back()->with('success', 'Khóa học đã bị reject.');
    }

    public function hide(Request $request, $id)
    {
        $validated = $request->validate([
            'review_note' => 'nullable|string|max:5000',
        ]);
        $course = Course::findOrFail($id);
        $old = $course->only([
            'approval_status',
            'approval_note',
            'status',
            'reviewed_by',
            'reviewed_at',
        ]);
        $course->update([
            'approval_status' => 'hidden',
            'status' => 0,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
        ]);

        $this->adminAuditLogService->log(
            'course_hidden',
            'course',
            $course->id,
            $old,
            $course->fresh()->only([
                'approval_status',
                'approval_note',
                'status',
                'reviewed_by',
                'reviewed_at',
            ]),
            $validated['review_note'] ?? 'Khóa học đã bị ẩn bởi hệ thống quản trị',
            ['source' => 'admin_course_approval']
        );

        return back()->with('success', 'Khóa học đã bị hidden.');
    }

    public function show($id, CourseQualityChecklistService $checklistService)
    {
        $course = Course::with(['user.riskScore', 'category', 'subcategory'])->findOrFail($id);
        $qualityChecks = $checklistService->evaluate($course);

        return view('backend.admin.course-approval.show', compact('course', 'qualityChecks'));
    }
}
