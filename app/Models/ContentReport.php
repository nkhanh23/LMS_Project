<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentReport extends Model
{
    protected $guarded = [];

    protected $casts = [
        'content_snapshot' => 'array',
        'reviewed_at' => 'datetime',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporter_id', 'id');
    }

    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reported_user_id', 'id');
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by', 'id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id', 'id');
    }
}
