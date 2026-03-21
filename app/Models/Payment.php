<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'refunded_amount' => 'decimal:2',
        'refunded_at' => 'datetime',
        'provider_payload' => 'array',
    ];

    public function order()
    {
        return $this->hasMany(Order::class, 'payment_id', 'id');
    }

    public function refundRequests()
    {
        return $this->hasMany(RefundRequest::class, 'payment_id', 'id');
    }
}
