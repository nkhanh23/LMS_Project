<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusHistory extends Model
{
    protected $guarded = [];
    protected $casts = [
        'meta_json' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'id');
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class, 'payment_id', 'id');
    }

    public function actor()
    {
        return $this->belongsTo(User::class, 'actor_id', 'id');
    }
}
