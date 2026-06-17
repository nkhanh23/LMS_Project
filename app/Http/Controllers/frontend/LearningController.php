<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Models\LectureDiscussion;
use App\Models\LectureNote;
use App\Models\QuizAttempt;
use App\Services\EnrollmentService;
use App\Services\LearningProgressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    protected $enrollmentService;
    protected $learningProgressService;

    public function __construct(EnrollmentService $enrollmentService, LearningProgressService $learningProgressService)
    {
        $this->enrollmentService = $enrollmentService;
        $this->learningProgressService = $learningProgressService;
    }

    public function playCourse($slug)
    {
        $course = Course::where('course_name_slug', $slug)->firstOrFail();

        abort_unless(Auth::check() && Auth::user()->hasAccessToCourse($course), 403);

        $enrollment = $this->enrollmentService->getActiveEnrollment(Auth::id(), $course->id);

        if (!$enrollment) {
            abort(403, 'Bạn chưa được ghi danh vào khóa học này.');
        }

        $resumeLecture = $this->learningProgressService->getResumeLecture($enrollment);

        if (!$resumeLecture) {
            return back()->with('error', 'Khóa học này chưa có bài giảng nào.');
        }

        return redirect()->route('course.lecture.watch', [$slug, $resumeLecture->id]);
    }

    public function watchLecture($slug, $lecture_id)
    {
        $course = Course::where('course_name_slug', $slug)->firstOrFail();

        abort_unless(Auth::check() && Auth::user()->hasAccessToCourse($course), 403);

        $enrollment = $this->enrollmentService->getActiveEnrollment(Auth::id(), $course->id);

        if (!$enrollment) {
            abort(403);
        }

        $currentLecture = CourseLecture::with('course.instructor', 'section', 'quiz.questions.options')
            ->where('id', $lecture_id)
            ->where('course_id', $course->id)
            ->first();

        if (!$currentLecture) {
            return redirect()->route('course.play', $slug);
        }

        if ($currentLecture->quiz && $currentLecture->quiz->shuffle_questions) {
            $currentLecture->quiz->setRelation('questions', $currentLecture->quiz->questions->shuffle());
        }

        if (!$this->learningProgressService->isLectureUnlocked($course, $enrollment, $currentLecture)) {
            return redirect()->route('course.play', $slug)
                ->with('error', 'Bạn cần hoàn thành bài trước đó để mở bài này.');
        }

        $sections = CourseSection::where('course_id', $course->id)
            ->with(['lecture.quiz'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();

        $lessonProgressMap = \App\Models\LessonProgress::where('enrollment_id', $enrollment->id)
            ->get()
            ->keyBy('lecture_id');

        $courseProgress = $enrollment->courseProgress;

        $discussions = LectureDiscussion::with('user')
            ->where('lecture_id', $currentLecture->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        $notes = LectureNote::where('lecture_id', $currentLecture->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $quizAttempt = null;
        $userAttemptsCount = 0;

        if (session('quiz_attempt_id')) {
            $quizAttempt = QuizAttempt::with('answers.question', 'answers.selectedOption')
                ->find(session('quiz_attempt_id'));
        }

        if ($currentLecture->quiz && Auth::check()) {
            $userAttemptsCount = QuizAttempt::where('quiz_id', $currentLecture->quiz->id)
                ->where('user_id', Auth::id())
                ->count();
        }

        $this->learningProgressService->markLectureInProgress($enrollment, $currentLecture);

        $isCompleted = isset($lessonProgressMap[$currentLecture->id]) && $lessonProgressMap[$currentLecture->id]->status === 'completed';

        return view('frontend.pages.learning.index', compact(
            'course',
            'sections',
            'currentLecture',
            'discussions',
            'notes',
            'quizAttempt',
            'userAttemptsCount',
            'enrollment',
            'courseProgress',
            'lessonProgressMap',
            'isCompleted'
        ));
    }

    public function getLectureData(CourseLecture $lecture)
    {
        $lecture->load(['course', 'quiz.questions.options']);

        if ($lecture->quiz && $lecture->quiz->shuffle_questions) {
            $lecture->quiz->setRelation('questions', $lecture->quiz->questions->shuffle());
        }

        $discussions = LectureDiscussion::with(['user', 'replies.user', 'replies'])
            ->where('lecture_id', $lecture->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        $quizAttempt = null;
        $userAttemptsCountTotal = 0; // Initialize to 0
        if ($lecture->quiz && Auth::check()) { // Check if quiz exists and user is authenticated
            $quizAttempt = QuizAttempt::where('quiz_id', $lecture->quiz->id)
                ->where('user_id', Auth::id())
                ->latest()
                ->first();

            $userAttemptsCountTotal = QuizAttempt::where('quiz_id', $lecture->quiz->id)
                ->where('user_id', Auth::id())
                ->count();
        }

        $enrollment = $this->enrollmentService->getActiveEnrollment(Auth::id(), $lecture->course_id);
        $isCompleted = false;
        if ($enrollment) {
            $isCompleted = \App\Models\LessonProgress::where('enrollment_id', $enrollment->id)
                ->where('lecture_id', $lecture->id)
                ->where('status', 'completed')
                ->exists();
        }

        $playerHtml = view('frontend.pages.learning.partials.player-content', [
            'currentLecture' => $lecture,
            'course' => $lecture->course,
            'quiz' => $lecture->quiz,
            'quizAttempt' => $quizAttempt,
            'userAttemptsCount' => $userAttemptsCountTotal,
            'isCompleted' => $isCompleted,
        ])->render();

        $qnaHtml = view('frontend.pages.learning.partials.qna-list', [
            'discussions' => $discussions,
            'course' => $lecture->course,
            'currentLecture' => $lecture,
        ])->render();

        $notes = LectureNote::where('lecture_id', $lecture->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $notesHtml = view('frontend.pages.learning.partials.note-list', [
            'notes' => $notes,
            'course' => $lecture->course,
            'currentLecture' => $lecture,
        ])->render();

        return response()->json([
            'status' => 'success',
            'lecture' => [
                'id' => $lecture->id,
                'course_id' => $lecture->course_id,
                'title' => $lecture->lecture_title,
                'type' => $lecture->type,
                'url' => $lecture->url,
                'content' => $lecture->content,
                'video_duration' => $lecture->video_duration,
                'has_quiz' => $lecture->quiz ? true : false,
            ],
            'player_html' => $playerHtml,
            'qna_html' => $qnaHtml,
            'notes_html' => $notesHtml,
        ]);
    }

    public function updateProgress(Request $request, CourseLecture $lecture)
    {
        $request->validate([
            'watch_seconds' => ['nullable', 'integer', 'min:0'],
        ]);

        $enrollment = $this->enrollmentService->getActiveEnrollment(Auth::id(), $lecture->course_id);

        abort_if(!$enrollment, 403);

        $progress = $this->learningProgressService->markLectureInProgress(
            $enrollment,
            $lecture,
            (int) $request->input('watch_seconds', 0)
        );

        return response()->json([
            'status' => 'success',
            'progress' => $progress,
            'course_progress' => $enrollment->fresh('courseProgress')->courseProgress,
        ]);
    }

    public function completeLecture(CourseLecture $lecture)
    {
        $enrollment = $this->enrollmentService->getActiveEnrollment(Auth::id(), $lecture->course_id);

        abort_if(!$enrollment, 403);

        $progress = $this->learningProgressService->markLectureCompleted($enrollment, $lecture);

        return response()->json([
            'status' => 'success',
            'progress' => $progress,
            'course_progress' => $enrollment->fresh('courseProgress')->courseProgress,
        ]);
    }
}
