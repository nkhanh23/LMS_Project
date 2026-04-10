<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meta_json' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citations()
    {
        return $this->hasMany(\App\Models\AiMessageCitation::class, 'message_id');
    }
}
