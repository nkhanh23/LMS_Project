<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseRequest;
use App\Models\Category;
use App\Models\Course;
use App\Models\CourseGoal;
use Illuminate\Http\Request;
use App\Service\CourseService;
use Illuminate\Support\Facades\Auth;

class CourseController extends Controller
{

    protected $courseService;
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $instructor_id = Auth::user()->id;
        $all_courses = Course::with(['category', 'subcategory'])->where('instructor_id', $instructor_id)->latest()->get();
        return view('backend.instructor.course.index', compact('all_courses'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $all_categories = Category::all();
        return view('backend.instructor.course.create', compact('all_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseRequest $request)
    {
        $validatedData = $request->validated();
        //Pass dữ liệu và file sang service
        $course = $this->courseService->createCourse($validatedData, $request->file('image'));
        //quản lý mục tiêu khóa học
        if (!empty($validatedData['course_goals'])) {
            $this->courseService->createCourseGoals($course->id, $validatedData['course_goals']);
        }
        return redirect()->back()->with('success', 'Khóa học đã được thêm thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $course = Course::with('subcategory')->find($id);
        $all_categories = Category::all();
        $course_goals = CourseGoal::where('course_id', $id)->get();
        return view('backend.instructor.course.edit', compact('course', 'all_categories', 'course_goals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CourseRequest $request, string $id)
    {
        $validatedData = $request->validated();
        //Pass dữ liệu và file sang service
        $course = $this->courseService->updateCourse($validatedData, $request->file('image'), $id);
        //quản lý mục tiêu khóa học
        if (!empty($validatedData['course_goals'])) {
            $this->courseService->updateCourseGoals($course->id, $validatedData['course_goals']);
        }
        return redirect()->back()->with('success', 'Khóa học đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $course = Course::findOrFail($id);
        if ($course->course_image) {
            $imagePath = public_path(parse_url($course->course_image, PHP_URL_PATH));
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $course->delete();
        return redirect()->route('instructor.course.index')->with('success', 'Khóa học đã được xóa thành công');
    }
}
