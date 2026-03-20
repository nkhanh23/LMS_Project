<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Services\AdminCourseService;
use App\Models\Category;
use App\Models\User;
use Illuminate\Http\Request;

class AdminCourseController extends Controller
{
    protected $courseService;

    public function __construct(AdminCourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $categoryId = $request->input('category_id');
        $instructorId = $request->input('instructor_id');

        $all_courses = $this->courseService->getCourses($search, $categoryId, $instructorId, 10);
        $categories = Category::orderBy('name', 'asc')->get();
        $instructors = User::where('role', 'instructor')->orderBy('name', 'asc')->get();

        return view('backend.admin.course.index', compact('all_courses', 'categories', 'instructors'));
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $course = Course::where('id', $id)->with('user', 'category', 'subCategory')->first();
        return view('backend.admin.course.view', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
