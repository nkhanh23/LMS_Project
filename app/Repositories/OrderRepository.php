<?php

namespace App\Repositories;

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
     * Lấy danh sách đơn hàng có phân trang kèm theo bộ lọc
     */
    public function getFilteredOrdersPaginated(array $filters, int $perPage = 15)
    {
        // Thêm with('payment') để tối ưu N+1 Query (Eager Loading)
        $query = $this->model->query()->with('payment');

        // 1. Lọc theo ngày
        if (!empty($filters['start_date'])) {
            $query->whereDate('created_at', '>=', Carbon::parse($filters['start_date']));
        }
        if (!empty($filters['end_date'])) {
            $query->whereDate('created_at', '<=', Carbon::parse($filters['end_date']));
        }

        // 2. Lọc theo khoảng giá (Sửa 'amount' thành 'price' chuẩn với DB)
        if (isset($filters['min_amount']) && $filters['min_amount'] !== '') {
            $query->where('price', '>=', (float) $filters['min_amount']);
        }
        if (isset($filters['max_amount']) && $filters['max_amount'] !== '') {
            $query->where('price', '<=', (float) $filters['max_amount']);
        }

        // 3. Lọc theo phương thức thanh toán (Truy vấn chéo sang bảng Payments)
        // Lưu ý: Dùng cột 'payment_type' chuẩn theo Database của bạn
        if (!empty($filters['payment_method'])) {
            $query->whereHas('payment', function ($q) use ($filters) {
                $q->where('payment_type', $filters['payment_method']);
            });
        }

        return $query->latest()
            ->paginate($perPage)
            ->withQueryString();
    }
}
