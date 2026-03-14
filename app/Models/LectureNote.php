<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LectureNote extends Model
{
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
}
