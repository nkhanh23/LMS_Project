<?php

namespace App\Repositories;

use App\Models\Course;

class AdminCourseRepository
{
    public function getCourses($search = null, $categoryId = null, $instructorId = null, $perPage = 10)
    {
        return Course::with(['user', 'category'])
            ->when($search, function ($query, $search) {
                return $query->where('course_name', 'LIKE', "%{$search}%");
            })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($instructorId, function ($query, $instructorId) {
                return $query->where('instructor_id', $instructorId);
            })
            ->latest()
            ->paginate($perPage);
    }
}
