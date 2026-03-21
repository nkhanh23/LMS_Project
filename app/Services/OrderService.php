<?php

namespace App\Services;

use App\Repositories\OrderRepository;

class OrderService
{
    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository)
    {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Xử lý logic nghiệp vụ cho việc lọc đơn hàng
     */
    public function getAdminOrders(array $requestData)
    {
        $filters = [
            'start_date'     => $requestData['start_date'] ?? null,
            'end_date'       => $requestData['end_date'] ?? null,
            'min_amount'     => $requestData['min_amount'] ?? null,
            'max_amount'     => $requestData['max_amount'] ?? null,
            'payment_method' => $requestData['payment_method'] ?? null,
            'status'         => $requestData['status'] ?? null,
            'refund_status'  => $requestData['refund_status'] ?? null,
        ];

        return $this->orderRepository->getFilteredOrdersPaginated($filters);
    }

    public function getUserOrders(int $userId, int $perPage = 10)
    {
        return $this->orderRepository->getUserOrdersPaginated($userId, $perPage);
    }

    public function getUserOrderDetail(int $userId, int $orderId)
    {
        return $this->orderRepository->getUserOrderDetail($userId, $orderId);
    }

    public function getAdminOrderDetail(int $orderId)
    {
        return $this->orderRepository->getAdminOrderDetail($orderId);
    }

    public function userHasActivePurchasedOrder(int $userId, int $courseId): bool
    {
        return $this->orderRepository->userHasActivePurchasedOrder($userId, $courseId);
    }
}
