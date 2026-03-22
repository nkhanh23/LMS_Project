<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAuditLog extends Model
{
    protected $guarded = [];

    protected $casts = [
        'old_values_json' => 'array',
        'new_values_json' => 'array',
        'context_json' => 'array',
    ];

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}
