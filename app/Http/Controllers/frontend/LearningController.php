<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Models\LectureDiscussion;
use App\Models\LectureNote;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
        // Lấy dữ liệu khóa học
        $course = Course::where('course_name_slug', $slug)->first();

        if (!$course) {
            abort(404);
        }

        // Lấy dữ liệu bài học hiện tại và kiểm tra tính hợp lệ
        $currentLecture = CourseLecture::with('course.instructor', 'section', 'quiz.questions.options')
            ->where('id', $lecture_id)
            ->where('course_id', $course->id)
            ->first();

        if (!$currentLecture) {
            // Nếu không tìm thấy bài giảng cụ thể, quay về bài giảng đầu tiên của khóa
            return redirect()->route('course.play', $slug);
        }

        // Lấy toàn bộ nội dung khóa học để hiển thị sidebar
        $sections = CourseSection::where('course_id', $course->id)->with('lecture.quiz')->get();

        $discussions = LectureDiscussion::with('user')
            ->where('lecture_id', $currentLecture->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        // Lấy ghi chú của người dùng
        $notes = LectureNote::where('lecture_id', $currentLecture->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Lấy kết quả quiz vừa nộp (nếu có)
        $quizAttempt = null;
        $userAttemptsCount = 0;
        if (session('quiz_attempt_id')) {
            $quizAttempt = QuizAttempt::with('answers.question', 'answers.selectedOption')->find(session('quiz_attempt_id'));
        }

        if ($currentLecture->quiz && Auth::check()) {
            $userAttemptsCount = QuizAttempt::where('quiz_id', $currentLecture->quiz->id)
                ->where('user_id', Auth::id())
                ->count();
        }

        // Trả về view
        return view('frontend.pages.learning.index', compact('course', 'sections', 'currentLecture', 'discussions', 'notes', 'quizAttempt', 'userAttemptsCount'));
    }

    public function getLectureData(CourseLecture $lecture)
    {
        $lecture->load(['course', 'quiz']);

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

        $playerHtml = view('frontend.pages.learning.partials.player-content', [
            'currentLecture' => $lecture,
            'course' => $lecture->course,
            'quiz' => $lecture->quiz,
            'quizAttempt' => $quizAttempt,
            'userAttemptsCount' => $userAttemptsCountTotal,
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
}
