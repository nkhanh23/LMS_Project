<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class InstructorSalesService
{
    protected $orderRepository;
    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    //Lấy dữ liệu dashboard của giảng viên
    public function getDashboardData(int $instructorId, array $filters = []): array
    {
        return [
            'summary' => $this->orderRepository->getInstructorSummary($instructorId, $filters),
            'dailyRevenue' => $this->orderRepository->getInstructorRevenueByDay($instructorId, $filters),
            'monthlyRevenue' => $this->orderRepository->getInstructorRevenueByMonth($instructorId, $filters),
            'topCourses' => $this->orderRepository->getTopSellingCourses($instructorId, $filters),
        ];
    }

    //Lấy danh sách đơn hàng của giảng viên
    public function getOrdersData(int $instructorId, array $filters = [])
    {
        return $this->orderRepository->getInstructorOrders($instructorId, $filters);
    }

    //Lấy danh sách khóa học của giảng viên
    public function getInstructorCourses(int $instructorId)
    {
        return $this->orderRepository->getInstructorCourses($instructorId);
    }

    //Lấy chi tiết đơn hàng của giảng viên
    public function getOrderDetail(int $instructorId, int $orderId)
    {
        return $this->orderRepository->getInstructorOrderDetail($instructorId, $orderId);
    }

    //Lấy header cho file export
    public function getOrdersExportHeaders(): array
    {
        return [
            'Order ID',
            'Student Name',
            'Student Email',
            'Course',
            'Gross Amount',
            'Platform Amount',
            'Net Amount',
            'Status',
            'Payment Type',
            'Invoice',
            'Paid At',
        ];
    }

    //Lấy dữ liệu đơn hàng của giảng viên để export
    public function getOrdersExportData(int $instructorId, array $filters = []): array
    {
        $orders = $this->orderRepository->getInstructorOrdersForExport($instructorId, $filters);

        return $orders->map(function ($order) {
            return [
                $order->id,
                $order->user->name ?? 'N/A',
                $order->user->email ?? 'N/A',
                $order->course_title ?? ($order->course->course_name ?? 'N/A'),
                $order->gross_amount ?? $order->price ?? 0,
                $order->platform_amount ?? 0,
                $order->net_amount ?? $order->price ?? 0,
                $order->status ?? 'completed',
                $order->payment->payment_type ?? 'N/A',
                $order->payment->invoice_no ?? 'N/A',
                optional($order->paid_at)?->format('Y-m-d H:i:s') ?? 'N/A',
            ];
        })->toArray();
    }
}
