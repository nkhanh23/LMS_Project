<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = [];

    protected $casts = [
        'paid_at' => 'datetime',
        'refund_requested_at' => 'datetime',
        'refunded_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'access_revoked_at' => 'datetime',
        'gross_amount' => 'decimal:2',
        'net_amount' => 'decimal:2',
        'platform_amount' => 'decimal:2',
        'refund_amount' => 'decimal:2',
    ];

    // Order thuộc về 1 User (người mua)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Order thuộc về 1 Instructor
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    // Order thuộc về 1 Course
    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    // Order thuộc về 1 Payment
    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    // Order có nhiều RefundRequest
    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class, 'order_id', 'id');
    }

    // Order có nhiều OrderStatusHistory
    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
    }

    // Kiểm tra học viên còn quyền truy cập khoá không
    public function isAccessible(): bool
    {
        return $this->status === 'completed'  // Đơn hoàn thành
            && !in_array($this->refund_status, ['approved', 'processed']) // Đơn không bị refund
            && is_null($this->access_revoked_at); // Đơn không bị thu hồi quyền truy cập
    }

    // Kiểm tra đơn có thể xin hoàn tiền không
    public function isRefundable(): bool
    {
        return $this->status === 'completed' // Đơn hoàn thành
            && !in_array($this->refund_status, ['requested', 'approved', 'processed']); // Đơn không bị refund
    }

    // Kiểm tra đơn có thể hủy không (chỉ khi pending)
    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending']); // Đơn chờ xử lý
    }

    public function getBaseAmountAttribute(): float
    {
        return (float) ($this->gross_amount ?? $this->price ?? 0);
    }

    // Order có 1 Enrollment
    public function enrollment()
    {
        return $this->hasOne(Enrollment::class, 'order_id');
    }
}
