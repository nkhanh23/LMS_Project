<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Payment;
use App\Models\RefundRequest;

class RefundRepository
{
    /**
     * Danh sách refund request cho admin
     */
    public function getRefundRequestsPaginated(array $filters = [], int $perPage = 15)
    {
        return RefundRequest::query()
            ->with(['order.course', 'order.user', 'payment', 'user', 'reviewer'])
            ->when(!empty($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(!empty($filters['type']), function ($query) use ($filters) {
                $query->where('type', $filters['type']);
            })
            ->latest()
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Lấy chi tiết refund request
     */
    public function findRefundRequestById(int $id): RefundRequest
    {
        return RefundRequest::query()
            ->with(['order', 'payment', 'user'])
            ->findOrFail($id);
    }

    /**
     * Tìm refund request đang mở của 1 order
     */
    public function findOpenRefundRequestByOrderId(int $orderId): ?RefundRequest
    {
        return RefundRequest::query()
            ->where('order_id', $orderId)
            ->whereIn('status', ['pending', 'approved'])
            ->latest()
            ->first();
    }

    /**
     * Lock order + eager load relation để xử lý transaction refund/cancel
     */
    public function lockOrderWithRelations(int $orderId): Order
    {
        return Order::query()
            ->with(['payment', 'course', 'user', 'refundRequests'])
            ->lockForUpdate()
            ->findOrFail($orderId);
    }

    /**
     * Tạo refund request
     */
    public function createRefundRequest(array $data): RefundRequest
    {
        return RefundRequest::create($data);
    }

    /**
     * Cập nhật refund request
     */
    public function updateRefundRequest(RefundRequest $refundRequest, array $data): RefundRequest
    {
        $refundRequest->update($data);
        return $refundRequest->refresh();
    }

    /**
     * Cập nhật order
     */
    public function updateOrder(Order $order, array $data): Order
    {
        $order->update($data);
        return $order->refresh();
    }

    /**
     * Cập nhật payment
     */
    public function updatePayment(?Payment $payment, array $data): ?Payment
    {
        if (!$payment) {
            return null;
        }

        $payment->update($data);
        return $payment->refresh();
    }

    /**
     * Tạo lịch sử trạng thái order
     */
    public function createStatusHistory(array $data): OrderStatusHistory
    {
        return OrderStatusHistory::create($data);
    }
}
