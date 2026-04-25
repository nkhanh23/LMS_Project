<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AiChatSession;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Support\Facades\Auth;

class UserLearningController extends Controller
{
    public function myCourses()
    {
        $userId = Auth::id();

        $enrollments = Enrollment::with([
            'course.instructor',
            'courseProgress.lastLecture',
            'lastLecture',
        ])
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->latest('last_accessed_at')
            ->paginate(9);

        return view('backend.user.my-courses.index', compact('enrollments'));
    }

    public function continueLearning()
    {
        $userId = Auth::id();

        $enrollments = Enrollment::with([
            'course.instructor',
            'courseProgress.lastLecture',
            'lastLecture',
        ])
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->where(function ($query) {
                $query->whereNotNull('last_lecture_id')
                    ->orWhereHas('courseProgress', function ($q) {
                        $q->whereNotNull('last_lecture_id');
                    });
            })
            ->latest('last_accessed_at')
            ->limit(10)
            ->get();

        return view('backend.user.continue-learning.index', compact('enrollments'));
    }

    public function quizHistory()
    {
        $attempts = QuizAttempt::with([
            'quiz.lecture.course',
        ])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('backend.user.quiz-history.index', compact('attempts'));
    }

    public function quizAttemptDetail(QuizAttempt $attempt)
    {
        abort_unless($attempt->user_id === Auth::id(), 403);

        $attempt->load([
            'quiz.lecture.course',
            'answers.question',
            'answers.selectedOption',
        ]);

        return view('backend.user.quiz-history.show', compact('attempt'));
    }

    public function aiTutorHistory()
    {
        $sessions = AiChatSession::with([
            'course',
            'lecture',
            'messages',
        ])
            ->where('user_id', Auth::id())
            ->latest('last_activity_at')
            ->paginate(10);

        return view('backend.user.ai-tutor.history', compact('sessions'));
    }

    public function aiTutorSessionDetail(AiChatSession $session)
    {
        abort_unless($session->user_id === Auth::id(), 403);

        $session->load([
            'course',
            'lecture',
            'messages.citations.document',
            'messages.citations.chunk',
        ]);

        return view('backend.user.ai-tutor.show', compact('session'));
    }
}
