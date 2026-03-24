<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Course;
use App\Models\CourseSection;

class CourseLecture extends Model
{
    protected $guarded = [];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function section()
    {
        return $this->belongsTo(CourseSection::class);
    }

    public function quiz()
    {
        return $this->hasOne(Quiz::class, 'lecture_id', 'id');
    }

    public function progresses()
    {
        return $this->hasMany(LessonProgress::class, 'lecture_id');
    }
}
