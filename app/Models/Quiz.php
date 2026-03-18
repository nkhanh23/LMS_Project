<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id', 'id');
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class, 'section_id', 'id');
    }

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id', 'id');
    }

    public function questions()
    {
        return $this->hasMany(QuizQuestion::class, 'quiz_id', 'id')->orderBy('sort_order');
    }

    public function attempts()
    {
        return $this->hasMany(QuizAttempt::class, 'quiz_id', 'id');
    }
}
