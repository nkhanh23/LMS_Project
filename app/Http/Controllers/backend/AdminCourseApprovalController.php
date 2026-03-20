<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class AdminCourseApprovalController extends Controller
{
    public function index(Request $request)
    {
        $courseStatus = $request->input('course_status');

        $courses = Course::query()
            ->with(['user', 'category'])
            ->when($courseStatus, function ($query) use ($courseStatus) {
                $query->where('approval_status', $courseStatus);
            })
            ->latest()
            ->paginate(10);

        return view('backend.admin.course-approval.index', compact('courses', 'courseStatus'));
    }

    public function publish($id)
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

    public function reject(Request $request, $id)
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

    public function hide($id)
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
