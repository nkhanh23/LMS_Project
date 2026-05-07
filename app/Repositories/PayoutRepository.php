<?php

namespace App\Repositories;

use App\Models\PayoutRequest;
use App\Models\Order;

class PayoutRepository
{
    // Lấy tổng doanh thu Net thực tế
    public function getTotalNetRevenue(int $instructorId): float
    {
        return (float) Order::where('instructor_id', $instructorId)
            ->where('status', 'completed')
            ->sum('net_amount');
    }

    // Lấy tổng tiền đã rút hoặc đang chờ xử lý
    public function getTotalWithdrawn(int $instructorId): float
    {
        return (float) PayoutRequest::where('instructor_id', $instructorId)
            ->whereIn('status', ['pending', 'approved'])
            ->sum('amount');
    }

    // Lưu request mới
    public function createPayout(array $data): PayoutRequest
    {
        return PayoutRequest::create($data);
    }

    // Lấy lịch sử
    public function getHistory(int $instructorId, int $perPage = 10)
    {
        return PayoutRequest::where('instructor_id', $instructorId)
            ->latest()
            ->paginate($perPage);
    }
}
