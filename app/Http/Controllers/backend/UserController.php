<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    public function dashboard()
    {
        return view('backend.user.index');
    }

    public function myCourses(Request $request)
    {
        $user = Auth::user();
        $status = $request->query('status', 'all');

        $enrollments = Enrollment::query()
            ->with([
                'course.user',
                'courseProgress',
            ])
            ->where('user_id', $user->id)
            ->where('status', 'active')
            ->latest('access_granted_at')
            ->get();

        $courses = $enrollments->map(function ($enrollment) {
            $course = $enrollment->course;
            $progress = $enrollment->courseProgress;

            $completionPercent = (int) ($progress->completion_percent ?? 0);
            $completedLectures = (int) ($progress->completed_lectures ?? 0);
            $totalLectures = (int) ($progress->total_lectures ?? 0);

            if ($completionPercent >= 100) {
                $displayStatus = 'completed';
            } elseif ($completionPercent > 0) {
                $displayStatus = 'learning';
            } else {
                $displayStatus = 'learning';
            }

            return [
                'enrollment_id' => $enrollment->id,
                'course_id' => $course?->id,
                'title' => $course?->course_name ?? 'Khóa học chưa có tên',
                'slug' => $course?->course_name_slug,
                'instructor_name' => $course?->user?->name ?? 'Chưa có giảng viên',
                'completion_percent' => $completionPercent,
                'completed_lectures' => $completedLectures,
                'total_lectures' => $totalLectures,
                'display_status' => $displayStatus,
                'access_granted_at' => $enrollment->access_granted_at,
            ];
        });

        $courses = match ($status) {
            'learning' => $courses->where('display_status', 'learning')->values(),
            'completed' => $courses->where('display_status', 'completed')->values(),
            default => $courses->values(),
        };
        return view('frontend.pages.course.index', [
            'courses' => $courses,
            'status' => $status,
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
