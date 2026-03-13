<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function playCourse($slug)
    {
        $course = Course::where('course_name_slug', $slug)->firstOrFail();
        $firstLecture = CourseLecture::where('course_id', $course->id)->orderBy('id', 'asc')->first();

        if (!$firstLecture) {
            return back()->with('error', 'Khóa học này chưa có bài giảng nào.');
        }

        return redirect()->route('course.lecture.watch', [$slug, $firstLecture->id]);
    }

    public function watchLecture($slug, $lecture_id)
    {
        // 1. Lấy dữ liệu khóa học
        $course = Course::where('course_name_slug', $slug)->first();

        if (!$course) {
            abort(404);
        }

        // 2. Lấy dữ liệu bài học hiện tại và kiểm tra tính hợp lệ
        $currentLecture = CourseLecture::with('course.instructor', 'section')
            ->where('id', $lecture_id)
            ->where('course_id', $course->id)
            ->first();

        if (!$currentLecture) {
            // Nếu không tìm thấy bài giảng cụ thể, quay về bài giảng đầu tiên của khóa
            return redirect()->route('course.play', $slug);
        }

        // 3. Lấy toàn bộ nội dung khóa học để hiển thị sidebar
        $sections = CourseSection::where('course_id', $course->id)->with('lecture')->get();

        // 4. Trả về view
        return view('frontend.pages.learning.index', compact('course', 'sections', 'currentLecture'));
    }
}
