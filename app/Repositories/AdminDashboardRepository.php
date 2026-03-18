<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\InstructorRequest;
use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
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

            'pending_courses' => Course::where('approval_status', 'pending')->count(),

            'pending_instructors' => InstructorRequest::where('status', 'pending')->count(),

            'payment_success' => $successPayments,
            'payment_pending' => $pendingPayments,
            'payment_failed' => $failedPayments,
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
}
