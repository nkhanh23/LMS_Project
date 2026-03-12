<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\SubCategory;

class Course extends Model
{
    protected $guarded = [];

    protected $casts = [
        'course_goals' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(SubCategory::class, 'subcategory_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function goals()
    {
        return $this->hasMany(CourseGoal::class, 'course_id', 'id');
    }

    public function sections()
    {
        return $this->hasMany(CourseSection::class, 'course_id', 'id');
    }

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id', 'id');
    }

    public function reviews()
    {
        return $this->hasMany(CourseReviews::class, 'course_id', 'id');
    }
}
