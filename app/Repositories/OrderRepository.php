<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\Order;
use Illuminate\Support\Carbon;

class OrderRepository
{
    protected $model;

    public function __construct(Order $model)
    {
        $this->model = $model;
    }

    /**
     * Lấy danh sách đơn hàng admin có phân trang kèm theo bộ lọc
     */
    public function getFilteredOrdersPaginated(array $filters, int $perPage = 15)
    {
        $query = $this->model->query()
            ->with(['payment', 'user', 'course', 'instructor']);

        // 1. Lọc theo ngày tạo order
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', Carbon::parse($filters['start_date']));
        }

        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', Carbon::parse($filters['end_date']));
        }

        // 2. Lọc theo khoảng giá
        if (isset($filters['min_amount']) && $filters['min_amount'] !== '') {
            $query->where('price', '>=', (float) $filters['min_amount']);
        }

        if (isset($filters['max_amount']) && $filters['max_amount'] !== '') {
            $query->where('price', '<=', (float) $filters['max_amount']);
        }

        // 3. Lọc theo phương thức thanh toán
        if (!empty($filters['payment_method'])) {
            $query->whereHas('payment', function ($q) use ($filters) {
                $q->where('payment_type', $filters['payment_method']);
            });
        }

        // 4. Lọc theo trạng thái order
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        // 5. Lọc theo refund_status
        if (!empty($filters['refund_status'])) {
            $query->where('refund_status', $filters['refund_status']);
        }

        return $query->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Danh sách order của user
     */
    public function getUserOrdersPaginated(int $userId, int $perPage = 10)
    {
        return $this->model->query()
            ->with(['course', 'payment', 'refundRequests'])
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Chi tiết 1 order của user
     */
    public function getUserOrderDetail(int $userId, int $orderId): Order
    {
        return $this->model->query()
            ->with(['course', 'payment', 'refundRequests', 'statusHistories'])
            ->where('user_id', $userId)
            ->where('id', $orderId)
            ->firstOrFail();
    }

    /**
     * Chi tiết 1 order cho admin
     */
    public function getAdminOrderDetail(int $orderId): Order
    {
        return $this->model->query()
            ->with([
                'user',
                'instructor',
                'course',
                'payment',
                'refundRequests',
                'statusHistories',
            ])
            ->where('id', $orderId)
            ->firstOrFail();
    }

    /**
     * Lock order để xử lý transaction refund/cancel
     */
    public function lockOrderById(int $orderId): Order
    {
        return $this->model->query()
            ->with(['payment', 'course', 'user', 'refundRequests'])
            ->lockForUpdate()
            ->findOrFail($orderId);
    }

    /**
     * Kiểm tra user còn order hợp lệ để truy cập course hay không
     */
    public function userHasActivePurchasedOrder(int $userId, int $courseId): bool
    {
        return $this->model->query()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'completed')
            ->whereNotIn('refund_status', ['approved', 'processed'])
            ->whereNull('access_revoked_at')
            ->exists();
    }

    /**
     * Base query đơn completed của giảng viên
     */
    public function baseInstructorCompletedOrdersQuery(int $instructorId)
    {
        return $this->model->query()
            ->where('instructor_id', $instructorId)
            ->where('status', 'completed');
    }

    /**
     * Lấy danh sách đơn hàng của giảng viên
     */
    public function getInstructorOrders(int $instructorId, array $filters = [])
    {
        $query = $this->baseInstructorCompletedOrdersQuery($instructorId)
            ->with(['user', 'course', 'payment']);

        if (!empty($filters['from_date'])) {
            $query->whereDate('paid_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('paid_at', '<=', $filters['to_date']);
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        return $query->orderByDesc('paid_at')->paginate(15);
    }

    /**
     * Lấy thông tin tổng quan doanh thu của giảng viên
     */
    public function getInstructorSummary(int $instructorId, array $filters = [])
    {
        $query = $this->baseInstructorCompletedOrdersQuery($instructorId);

        if (!empty($filters['from_date'])) {
            $query->whereDate('paid_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('paid_at', '<=', $filters['to_date']);
        }

        $totalOrders = (clone $query)->count();
        $totalRevenue = (clone $query)->sum('price');
        $totalCoursesSold = (clone $query)->count();
        $distinctCoursesSold = (clone $query)->distinct('course_id')->count('course_id');

        return [
            'total_orders' => $totalOrders,
            'total_revenue' => $totalRevenue,
            'total_courses_sold' => $totalCoursesSold,
            'distinct_courses_sold' => $distinctCoursesSold,
        ];
    }

    /**
     * Lấy doanh thu của giảng viên theo ngày
     */
    public function getInstructorRevenueByDay(int $instructorId, array $filters = [])
    {
        $query = $this->baseInstructorCompletedOrdersQuery($instructorId)
            ->selectRaw('DATE(paid_at) as report_date, SUM(price) as revenue, COUNT(*) as sold_count');

        if (!empty($filters['from_date'])) {
            $query->whereDate('paid_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('paid_at', '<=', $filters['to_date']);
        }

        return $query
            ->groupBy('report_date')
            ->orderBy('report_date')
            ->get();
    }

    /**
     * Lấy doanh thu của giảng viên theo tháng
     */
    public function getInstructorRevenueByMonth(int $instructorId, array $filters = [])
    {
        $query = $this->baseInstructorCompletedOrdersQuery($instructorId)
            ->selectRaw('
                EXTRACT(YEAR FROM paid_at) as year,
                EXTRACT(MONTH FROM paid_at) as month,
                SUM(price) as revenue,
                COUNT(*) as sold_count
            ');

        if (!empty($filters['from_date'])) {
            $query->whereDate('paid_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('paid_at', '<=', $filters['to_date']);
        }

        return $query
            ->groupByRaw('EXTRACT(YEAR FROM paid_at), EXTRACT(MONTH FROM paid_at)')
            ->orderByRaw('EXTRACT(YEAR FROM paid_at) ASC')
            ->orderByRaw('EXTRACT(MONTH FROM paid_at) ASC')
            ->get();
    }

    /**
     * Lấy khóa học bán chạy nhất của giảng viên
     */
    public function getTopSellingCourses(int $instructorId, array $filters = [], int $limit = 5)
    {
        $query = $this->baseInstructorCompletedOrdersQuery($instructorId)
            ->selectRaw('course_id, course_title, COUNT(*) as sold_count, SUM(price) as revenue');

        if (!empty($filters['from_date'])) {
            $query->whereDate('paid_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('paid_at', '<=', $filters['to_date']);
        }

        return $query
            ->groupBy('course_id', 'course_title')
            ->orderByDesc('sold_count')
            ->limit($limit)
            ->get();
    }

    /**
     * Lấy danh sách khóa học của giảng viên
     */
    public function getInstructorCourses(int $instructorId)
    {
        return Course::query()
            ->where('instructor_id', $instructorId)
            ->select('id', 'course_name')
            ->orderBy('course_name', 'asc')
            ->get();
    }

    /**
     * Lấy chi tiết đơn hàng của giảng viên
     */
    public function getInstructorOrderDetail(int $instructorId, int $orderId)
    {
        return $this->model->query()
            ->with(['user', 'course', 'payment'])
            ->where('instructor_id', $instructorId)
            ->where('id', $orderId)
            ->firstOrFail();
    }

    /**
     * Lấy danh sách đơn hàng của giảng viên để export
     */
    public function getInstructorOrdersForExport(int $instructorId, array $filters = [])
    {
        $query = $this->baseInstructorCompletedOrdersQuery($instructorId)
            ->with(['user', 'course', 'payment']);

        if (!empty($filters['from_date'])) {
            $query->whereDate('paid_at', '>=', $filters['from_date']);
        }

        if (!empty($filters['to_date'])) {
            $query->whereDate('paid_at', '<=', $filters['to_date']);
        }

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        return $query
            ->orderByDesc('paid_at')
            ->get();
    }
}
