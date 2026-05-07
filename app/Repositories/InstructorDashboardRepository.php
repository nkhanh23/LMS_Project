<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\Enrollment;
use App\Models\Order;
use App\Models\User;
use App\Models\QuizAttempt;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class InstructorDashboardRepository
{
    /**
     * Get basic summary stats for the instructor
     */
    public function getSummary(int $instructorId): array
    {
        $courses = Course::where('instructor_id', $instructorId)->get();
        $courseIds = $courses->pluck('id');

        $totalCourses = $courses->count();
        $activeCourses = $courses->where('approval_status', 'published')->where('status', '1')->count();

        $totalEnrollments = Enrollment::whereIn('course_id', $courseIds)->count();
        $totalStudents = Enrollment::whereIn('course_id', $courseIds)->distinct('user_id')->count();

        $totalRevenue = Order::where('instructor_id', $instructorId)
            ->where('status', 'completed')
            ->sum(DB::raw('COALESCE(gross_amount, price, 0)'));

        return [
            'total_courses' => $totalCourses,
            'active_courses' => $activeCourses,
            'total_students' => $totalStudents,
            'total_enrollments' => $totalEnrollments,
            'total_revenue' => (float)$totalRevenue,
        ];
    }

    /**
     * Week-over-week trends for the summary metrics
     */
    public function getTrendData(int $instructorId): array
    {
        $now = Carbon::now();
        $thisWeekStart = $now->copy()->subDays(6)->startOfDay();
        $lastWeekStart = $now->copy()->subDays(13)->startOfDay();
        $lastWeekEnd = $now->copy()->subDays(7)->endOfDay();

        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

        // Courses
        $coursesThisWeek = Course::where('instructor_id', $instructorId)->where('created_at', '>=', $thisWeekStart)->count();
        $coursesLastWeek = Course::where('instructor_id', $instructorId)->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        // Students (Enrollments users)
        $studentsThisWeek = Enrollment::whereIn('course_id', $courseIds)->where('created_at', '>=', $thisWeekStart)->distinct('user_id')->count();
        $studentsLastWeek = Enrollment::whereIn('course_id', $courseIds)->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->distinct('user_id')->count();

        // Enrollments
        $enrollThisWeek = Enrollment::whereIn('course_id', $courseIds)->where('created_at', '>=', $thisWeekStart)->count();
        $enrollLastWeek = Enrollment::whereIn('course_id', $courseIds)->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        // Revenue
        $revenueThisWeek = (float) Order::where('instructor_id', $instructorId)->where('status', 'completed')
            ->where('paid_at', '>=', $thisWeekStart)
            ->sum(DB::raw('COALESCE(gross_amount, price, 0)'));
        $revenueLastWeek = (float) Order::where('instructor_id', $instructorId)->where('status', 'completed')
            ->whereBetween('paid_at', [$lastWeekStart, $lastWeekEnd])
            ->sum(DB::raw('COALESCE(gross_amount, price, 0)'));

        return [
            'courses' => $this->calcTrend($coursesThisWeek, $coursesLastWeek),
            'students' => $this->calcTrend($studentsThisWeek, $studentsLastWeek),
            'enrollments' => $this->calcTrend($enrollThisWeek, $enrollLastWeek),
            'revenue' => $this->calcTrend($revenueThisWeek, $revenueLastWeek),
        ];
    }

    private function calcTrend($current, $previous): array
    {
        if ($previous == 0) {
            $percent = $current > 0 ? 100 : 0;
        } else {
            $percent = round((($current - $previous) / $previous) * 100, 1);
        }

        return [
            'current' => $current,
            'previous' => $previous,
            'percent' => abs($percent),
            'direction' => $percent >= 0 ? 'up' : 'down',
        ];
    }

    /**
     * Daily student signups for the last 30 days
     */
    public function getStudentAnalytics(int $instructorId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

        $results = Enrollment::whereIn('course_id', $courseIds)
            ->where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(DISTINCT user_id) as total')
            )
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - 1 - $i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d/m');
            $data[] = (int) ($results[$date] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * Daily revenue for the last 30 days
     */
    public function getRevenueChart(int $instructorId, int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $results = Order::where('instructor_id', $instructorId)
            ->where('status', 'completed')
            ->where('paid_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(paid_at) as date'),
                DB::raw('SUM(COALESCE(gross_amount, price, 0)) as total')
            )
            ->groupBy(DB::raw('DATE(paid_at)'))
            ->orderBy('date')
            ->pluck('total', 'date')
            ->toArray();

        $labels = [];
        $data = [];

        for ($i = 0; $i < $days; $i++) {
            $date = Carbon::now()->subDays($days - 1 - $i)->format('Y-m-d');
            $labels[] = Carbon::parse($date)->format('d/m');
            $data[] = (float) ($results[$date] ?? 0);
        }

        return [
            'labels' => $labels,
            'data' => $data,
        ];
    }

    /**
     * List of instructor's courses with stats
     */
    public function getMyCourses(int $instructorId, int $limit = 5)
    {
        return Course::withCount('enrollments')
            ->where('instructor_id', $instructorId)
            ->withAvg('courseProgressRecords as avg_progress', 'completion_percent')
            ->orderByDesc('enrollments_count')
            ->limit($limit)
            ->get()
            ->map(function ($course) {
                return [
                    'id' => $course->id,
                    'title' => $course->course_title,
                    'students' => $course->enrollments_count,
                    'avg_progress' => round($course->avg_progress ?? 0, 1),
                    'status' => $course->approval_status == 'published' && $course->status == '1' ? 'Published' : 'Draft/Pending',
                ];
            });
    }

    /**
     * Recent activities related to the instructor's courses
     */
    public function getRecentActivities(int $instructorId, int $limit = 10)
    {
        $courseIds = Course::where('instructor_id', $instructorId)->pluck('id');

        // Get recent enrollments
        $enrollments = Enrollment::with('user', 'course')
            ->whereIn('course_id', $courseIds)
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'enrollment',
                    'user' => $item->user->name ?? 'Student',
                    'action' => 'đăng ký khóa học',
                    'target' => $item->course->course_title ?? 'Course',
                    'time' => $item->created_at,
                    'icon' => 'person_add',
                    'color' => 'primary'
                ];
            });

        // Get recent course completions
        $completions = CourseProgress::with('user', 'course')
            ->whereIn('course_id', $courseIds)
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->limit($limit)
            ->get()
            ->map(function ($item) {
                return [
                    'type' => 'completion',
                    'user' => $item->user->name ?? 'Student',
                    'action' => 'hoàn thành khóa học',
                    'target' => $item->course->course_title ?? 'Course',
                    'time' => $item->completed_at,
                    'icon' => 'workspace_premium',
                    'color' => 'success'
                ];
            });

        // Combine, sort, and limit
        $activities = collect($enrollments)->merge($completions)
            ->sortByDesc('time')
            ->take($limit)
            ->values();

        return $activities;
    }
}
