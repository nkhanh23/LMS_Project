<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Enrollment extends Model
{
    protected $guarded = [];

    protected $casts = [
        'access_granted_at' => 'datetime',
        'access_expires_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'completed_at' => 'datetime',
        'revoked_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function lastLecture()
    {
        return $this->belongsTo(CourseLecture::class, 'last_lecture_id');
    }

    public function lessonProgress()
    {
        return $this->hasMany(LessonProgress::class);
    }

    public function courseProgress()
    {
        return $this->hasOne(CourseProgress::class);
    }

    public function isActive(): bool
    {
        return $this->status === 'active'
            && (is_null($this->access_expires_at) || $this->access_expires_at->isFuture());
    }
}
