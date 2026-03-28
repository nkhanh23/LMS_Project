<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AiChatSession extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function lecture(): BelongsTo
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(AiChatMessage::class, 'session_id');
    }
}
