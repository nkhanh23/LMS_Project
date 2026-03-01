<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseSection;
use App\Models\InfoBox;
use App\Models\Slider;
use Illuminate\Http\Request;

class FrontEndDashBoardController extends Controller
{
    public function home()
    {
        $all_slider = Slider::latest()->get();
        $all_info = InfoBox::latest()->get();
        $all_category = Category::inRandomOrder()->limit(6)->get();

        $categories = Category::all();
        $course_category = Category::with('course', 'course.user', 'course.goals')->get();
        return view('frontend.pages.home.index', compact('all_slider', 'all_info', 'all_category', 'categories', 'course_category'));
    }

    public function view($slug)
    {
        $course = Course::where('course_name_slug', $slug)->with('category', 'subcategory', 'user', 'goals')->first();
        $total_lecture = CourseSection::where('course_id', $course->id)->with('lecture')->get()->count();
        $course_content = CourseSection::where('course_id', $course->id)->with('lecture')->get();
        return view('frontend.pages.course-details.index', compact('course', 'course_content', 'total_lecture'));
    }
}
