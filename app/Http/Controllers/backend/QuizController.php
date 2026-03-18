<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\QuizStoreRequest;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Services\QuizService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuizController extends Controller
{
    protected $quizService;

    public function __construct(QuizService $quizService)
    {
        $this->quizService = $quizService;
    }

    public function index(Request $request)
    {
        $courseId = $request->course_id;
        $sectionId = $request->section_id;
        $lectureId = $request->lecture_id;

        $courses = Course::where('instructor_id', Auth::id())
            ->orderBy('course_name')
            ->get();

        $sections = collect();
        if ($courseId) {
            $sections = CourseSection::where('course_id', $courseId)
                ->orderBy('id')
                ->get();
        }

        $lectures = collect();
        if ($courseId) {
            $lectures = CourseLecture::where('course_id', $courseId)
                ->where('type', 'quiz')
                ->when($sectionId, function ($query) use ($sectionId) {
                    $query->where('section_id', $sectionId);
                })
                ->orderBy('id')
                ->get();
        }

        $quizzes = CourseLecture::with([
            'course',
            'section',
            'quiz.questions'
        ])
            ->where('type', 'quiz')
            ->whereHas('course', function ($query) {
                $query->where('instructor_id', Auth::id());
            })
            ->when($courseId, function ($query) use ($courseId) {
                $query->where('course_id', $courseId);
            })
            ->when($sectionId, function ($query) use ($sectionId) {
                $query->where('section_id', $sectionId);
            })
            ->when($lectureId, function ($query) use ($lectureId) {
                $query->where('id', $lectureId);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('backend.instructor.quiz.index', compact(
            'quizzes',
            'courses',
            'sections',
            'lectures',
            'courseId',
            'sectionId',
            'lectureId'
        ));
    }
    public function edit(CourseLecture $lecture)
    {
        $lecture->load('course', 'quiz.questions.options');

        abort_if($lecture->type !== 'quiz', 404);
        abort_if($lecture->course->instructor_id !== Auth::id(), 403);

        return view('backend.instructor.quiz.edit', compact('lecture'));
    }

    public function storeOrUpdate(QuizStoreRequest $request, CourseLecture $lecture)
    {
        $lecture->load('course');

        abort_if($lecture->type !== 'quiz', 404);
        abort_if($lecture->course->instructor_id !== Auth::id(), 403);

        $this->quizService->saveManualQuiz($lecture, $request->validated());

        return back()->with('success', 'Quiz đã được lưu thành công');
    }
}
