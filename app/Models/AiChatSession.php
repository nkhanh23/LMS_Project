<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AiChatSession extends Model
{
    protected $guarded = [];

    protected $casts = [
        'last_activity_at' => 'datetime',
        'closed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }

    public function messages()
    {
        return $this->hasMany(AiChatMessage::class, 'session_id');
    }

    public function latestMessages(int $limit = 50)
    {
        return $this->messages()
            ->with(['citations.document', 'citations.chunk'])
            ->latest('id')
            ->limit($limit);
    }

    public function close(): void
    {
        $this->update([
            'status' => 'closed',
            'closed_at' => now(),
        ]);
    }

    public function isWebsiteMode(): bool
    {
        return $this->mode === 'website';
    }

    public function isLessonMode(): bool
    {
        return $this->mode === 'lesson';
    }
}
