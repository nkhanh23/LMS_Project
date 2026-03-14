<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LectureDiscussion extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function parent()
    {
        return $this->belongsTo(LectureDiscussion::class, 'parent_id');
    }

    public function replies()
    {
        return $this->hasMany(LectureDiscussion::class, 'parent_id')
            ->with(['user', 'replies']);
    }
}
