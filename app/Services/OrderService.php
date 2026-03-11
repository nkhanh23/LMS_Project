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
        // Tại đây, bạn có thể thêm các Business Rules
        // Ví dụ: Nếu người dùng chọn start_date lớn hơn end_date, bạn có thể swap (hoán đổi) chúng ở đây.
        // Hoặc set giá trị mặc định nếu cần.

        $filters = [
            'start_date'     => $requestData['start_date'] ?? null,
            'end_date'       => $requestData['end_date'] ?? null,
            'min_amount'     => $requestData['min_amount'] ?? null,
            'max_amount'     => $requestData['max_amount'] ?? null,
            'payment_method' => $requestData['payment_method'] ?? null,
        ];

        return $this->orderRepository->getFilteredOrdersPaginated($filters);
    }
}
