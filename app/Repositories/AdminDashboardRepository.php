<?php

namespace App\Repositories;

use App\Models\AdminAuditLog;
use App\Models\ContentReport;
use App\Models\Course;
use App\Models\CourseProgress;
use App\Models\Enrollment;
use App\Models\InstructorRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\QuizAttempt;
use App\Models\RefundRequest;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardRepository
{
    public function getSummary(): array
    {
        $today = now()->toDateString();
        $startOfMonth = now()->startOfMonth()->toDateString();

        $paymentCounts = Payment::select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $successPayments = ($paymentCounts['success'] ?? 0) + ($paymentCounts['completed'] ?? 0);
        $pendingPayments = $paymentCounts['pending'] ?? 0;
        $failedPayments = ($paymentCounts['failed'] ?? 0) + ($paymentCounts['cancelled'] ?? 0);

        return [
            'total_users' => User::where('role', 'user')->count(),
            'total_instructors' => User::where('role', 'instructor')->count(),
            'total_courses' => Course::count(),
            'paid_orders' => Order::where('status', 'completed')->count(),

            'revenue_today' => Order::where('status', 'completed')
                ->whereDate('paid_at', $today)
                ->sum(DB::raw('COALESCE(gross_amount, price, 0)')),

            'revenue_month' => Order::where('status', 'completed')
                ->whereDate('paid_at', '>=', $startOfMonth)
                ->sum(DB::raw('COALESCE(gross_amount, price, 0)')),

            'pending_courses' => Course::where('approval_status', 'pending_review')->count(),

            'pending_instructors' => User::where('role', 'instructor')
                ->where('instructor_approval_status', 'pending')
                ->count(),

            'payment_success' => $successPayments,
            'payment_pending' => $pendingPayments,
            'payment_failed' => $failedPayments,
            'total_enrollments' => Enrollment::count(),
            'active_enrollments' => Enrollment::where('status', 'active')->count(),
            'completed_enrollments' => Enrollment::where('status', 'completed')->count(),
            'avg_learning_completion' => (int) round(CourseProgress::avg('completion_percent') ?? 0),
        ];
    }

    /**
     * So sánh tuần này vs tuần trước cho các KPI chính
     */
    public function getTrendData(): array
    {
        $now = Carbon::now();
        $thisWeekStart = $now->copy()->subDays(6)->startOfDay();
        $lastWeekStart = $now->copy()->subDays(13)->startOfDay();
        $lastWeekEnd = $now->copy()->subDays(7)->endOfDay();

        // Users
        $usersThisWeek = User::where('role', 'user')->where('created_at', '>=', $thisWeekStart)->count();
        $usersLastWeek = User::where('role', 'user')->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        // Instructors
        $instructorsThisWeek = User::where('role', 'instructor')->where('created_at', '>=', $thisWeekStart)->count();
        $instructorsLastWeek = User::where('role', 'instructor')->whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        // Courses
        $coursesThisWeek = Course::where('created_at', '>=', $thisWeekStart)->count();
        $coursesLastWeek = Course::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        // Orders
        $ordersThisWeek = Order::where('status', 'completed')->where('paid_at', '>=', $thisWeekStart)->count();
        $ordersLastWeek = Order::where('status', 'completed')->whereBetween('paid_at', [$lastWeekStart, $lastWeekEnd])->count();

        // Revenue
        $revenueThisWeek = (float) Order::where('status', 'completed')
            ->where('paid_at', '>=', $thisWeekStart)
            ->sum(DB::raw('COALESCE(gross_amount, price, 0)'));
        $revenueLastWeek = (float) Order::where('status', 'completed')
            ->whereBetween('paid_at', [$lastWeekStart, $lastWeekEnd])
            ->sum(DB::raw('COALESCE(gross_amount, price, 0)'));

        // Enrollments
        $enrollThisWeek = Enrollment::where('created_at', '>=', $thisWeekStart)->count();
        $enrollLastWeek = Enrollment::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])->count();

        return [
            'users' => $this->calcTrend($usersThisWeek, $usersLastWeek),
            'instructors' => $this->calcTrend($instructorsThisWeek, $instructorsLastWeek),
            'courses' => $this->calcTrend($coursesThisWeek, $coursesLastWeek),
            'orders' => $this->calcTrend($ordersThisWeek, $ordersLastWeek),
            'revenue' => $this->calcTrend($revenueThisWeek, $revenueLastWeek),
            'enrollments' => $this->calcTrend($enrollThisWeek, $enrollLastWeek),
        ];
    }

    /**
     * Tính % thay đổi + hướng trend
     */
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
     * Doanh thu theo ngày cho chart (30 ngày gần nhất)
     */
    public function getRevenueChartData(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $results = Order::where('status', 'completed')
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
     * Số user mới đăng ký theo ngày (30 ngày gần nhất)
     */
    public function getUserGrowthData(int $days = 30): array
    {
        $startDate = Carbon::now()->subDays($days - 1)->startOfDay();

        $results = User::where('created_at', '>=', $startDate)
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
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

    public function getTopCoursesByRevenue(int $limit = 5)
    {
        return Order::query()
            ->selectRaw('course_id, course_title, COUNT(*) as total_sales, SUM(COALESCE(gross_amount, price, 0)) as revenue')
            ->where('status', 'completed')
            ->groupBy('course_id', 'course_title')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    /**
     * Top instructors theo revenue
     */
    public function getTopInstructors(int $limit = 5)
    {
        return Order::query()
            ->join('users', 'orders.instructor_id', '=', 'users.id')
            ->selectRaw('orders.instructor_id, users.name as instructor_name, users.photo, COUNT(*) as total_sales, SUM(COALESCE(orders.gross_amount, orders.price, 0)) as revenue')
            ->where('orders.status', 'completed')
            ->groupBy('orders.instructor_id', 'users.name', 'users.photo')
            ->orderByDesc('revenue')
            ->limit($limit)
            ->get();
    }

    /**
     * Cảnh báo vận hành
     */
    public function getAlerts(): array
    {
        return [
            [
                'type' => 'warning',
                'icon' => 'pending_actions',
                'message' => 'courses chờ duyệt',
                'count' => Course::where('approval_status', 'pending_review')->count(),
                'url' => route('admin.course-approvals.index'),
            ],
            [
                'type' => 'danger',
                'icon' => 'currency_exchange',
                'message' => 'yêu cầu hoàn tiền chờ xử lý',
                'count' => RefundRequest::where('status', 'pending')->count(),
                'url' => route('admin.order.index'),
            ],
            [
                'type' => 'warning',
                'icon' => 'report',
                'message' => 'báo cáo nội dung chưa xử lý',
                'count' => ContentReport::where('status', 'pending')->count(),
                'url' => route('admin.moderation.reports.index'),
            ],
            [
                'type' => 'info',
                'icon' => 'person_add',
                'message' => 'yêu cầu instructor chờ duyệt',
                'count' => InstructorRequest::where('status', 'pending')->count(),
                'url' => route('admin.instructor-requests.index'),
            ],
        ];
    }

    /**
     * Activity feed từ AdminAuditLog
     */
    public function getRecentActivity(int $limit = 10)
    {
        return AdminAuditLog::with('admin')
            ->orderByDesc('created_at')
            ->limit($limit)
            ->get();
    }

    /**
     * System flow stats cho diagram
     */
    public function getSystemFlowStats(): array
    {
        return [
            'total_users' => User::count(),
            'total_enrollments' => Enrollment::count(),
            'total_courses' => Course::count(),
            'total_payments_completed' => Payment::whereIn('status', ['success', 'completed'])->count(),
            'total_revenue' => (float) Order::where('status', 'completed')->sum(DB::raw('COALESCE(gross_amount, price, 0)')),
            'total_quiz_attempts' => QuizAttempt::count(),
            'total_quiz_passed' => QuizAttempt::where('status', 'completed')->count(),
            'total_course_completed' => CourseProgress::whereNotNull('completed_at')->count(),
        ];
    }
}
