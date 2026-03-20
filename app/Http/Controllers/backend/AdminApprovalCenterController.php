<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\InstructorRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminApprovalCenterController extends Controller
{
    public function index(Request $request)
    {
        $instructorStatus = $request->input('instructor_status');
        $courseStatus = $request->input('course_status');

        $instructors = User::query()
            ->where('role', 'instructor')
            ->when($instructorStatus, function ($query) use ($instructorStatus) {
                $query->where('instructor_approval_status', $instructorStatus);
            })
            ->latest()
            ->paginate(10, ['*'], 'instructors_page');

        $courses = Course::query()
            ->with(['user', 'category'])
            ->when($courseStatus, function ($query) use ($courseStatus) {
                $query->where('approval_status', $courseStatus);
            })
            ->latest()
            ->paginate(10, ['*'], 'courses_page');

        $pendingRequests = InstructorRequest::with('user')
            ->where('status', 'pending')
            ->latest()
            ->get();

        return view('backend.admin.approval-center.index', compact(
            'instructors',
            'courses',
            'pendingRequests',
            'instructorStatus',
            'courseStatus'
        ));
    }

    public function approveInstructor(Request $request, $id)
    {
        $user = User::findOrFail($id);

        DB::transaction(function () use ($user) {
            $user->update([
                'role' => 'instructor',
                'status' => '1',
                'instructor_approval_status' => 'approved',
                'instructor_review_note' => null,
                'instructor_reviewed_by' => auth()->id(),
                'instructor_reviewed_at' => now(),
            ]);

            InstructorRequest::where('user_id', $user->id)
                ->where('status', 'pending')
                ->latest()
                ->first()?->update([
                    'status' => 'approved',
                    'reviewed_by' => auth()->id(),
                    'reviewed_at' => now(),
                ]);
        });

        return back()->with('success', 'Đã approve instructor.');
    }

    public function suspendInstructor(Request $request, $id)
    {
        $validated = $request->validate([
            'review_note' => 'required|string|max:2000',
        ]);

        $user = User::findOrFail($id);

        $user->update([
            'instructor_approval_status' => 'suspended',
            'instructor_review_note' => $validated['review_note'],
            'instructor_reviewed_by' => auth()->id(),
            'instructor_reviewed_at' => now(),
            'status' => '0',
        ]);

        return back()->with('success', 'Đã suspend instructor.');
    }

    public function publishCourse($id)
    {
        $course = Course::findOrFail($id);

        $course->update([
            'approval_status' => 'published',
            'approval_note' => null,
            'status' => 1,
            'approved_at' => now(),
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Khóa học đã được publish.');
    }

    public function rejectCourse(Request $request, $id)
    {
        $validated = $request->validate([
            'review_note' => 'required|string|max:5000',
        ]);

        $course = Course::findOrFail($id);

        $course->update([
            'approval_status' => 'rejected',
            'approval_note' => $validated['review_note'],
            'status' => 0,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Khóa học đã bị reject.');
    }

    public function hideCourse(Request $request, $id)
    {
        $course = Course::findOrFail($id);

        $course->update([
            'approval_status' => 'hidden',
            'status' => 0,
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        return back()->with('success', 'Khóa học đã bị hidden.');
    }
}
