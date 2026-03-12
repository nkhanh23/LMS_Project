<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function watchLecture($slug, $lecture_id)
    {
        // 1. Kiểm tra user đã mua khóa học chưa (Business Logic)
        // ...

        // 2. Lấy dữ liệu
        $course = Course::where('course_name_slug', $slug)->firstOrFail();
        $sections = CourseSection::where('course_id', $course->id)->with('lecture')->get();
        $currentLecture = CourseLecture::with('course.instructor', 'section')->where('id', $lecture_id)->first();

        // 3. Trả về view
        return view('frontend.pages.learning.index', compact('course', 'sections', 'currentLecture'));
    }
}
