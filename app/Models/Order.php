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

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class, 'order_id', 'id');
    }

    public function statusHistories()
    {
        return $this->hasMany(OrderStatusHistory::class, 'order_id', 'id');
    }

    public function isAccessible(): bool
    {
        return $this->status === 'completed'
            && !in_array($this->refund_status, ['approved', 'processed'])
            && is_null($this->access_revoked_at);
    }

    public function isRefundable(): bool
    {
        return $this->status === 'completed'
            && !in_array($this->refund_status, ['requested', 'approved', 'processed']);
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending']);
    }

    public function getBaseAmountAttribute(): float
    {
        return (float) ($this->gross_amount ?? $this->price ?? 0);
    }
}
