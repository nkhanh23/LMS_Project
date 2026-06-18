<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Models\AiChatSession;
use App\Models\Enrollment;
use App\Models\QuizAttempt;
use Illuminate\Http\Request;
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

    public function certificates()
    {
        $enrollments = Enrollment::with([
            'course.instructor',
            'courseProgress',
        ])
            ->where('user_id', Auth::id())
            ->where('status', 'active')
            ->whereHas('courseProgress', function ($query) {
                $query->where('completion_percent', '>=', 100);
            })
            ->whereHas('course', function ($query) {
                $query->whereIn('certificate', ['yes', 'Có', 'co']);
            })
            ->latest('completed_at')
            ->paginate(9);

        return view('backend.user.certificates.index', compact('enrollments'));
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

    public function aiTutorHistory(Request $request)
    {
        $activeMode = $request->string('mode')->toString();
        if (! in_array($activeMode, ['website', 'lesson'], true)) {
            $activeMode = 'lesson';
        }

        $sessions = AiChatSession::with([
            'course',
            'lecture',
            'messages' => fn($query) => $query->latest('id'),
        ])
            ->where('user_id', Auth::id())
            ->where('mode', $activeMode)
            ->latest('last_activity_at')
            ->paginate(10);

        $sessions->appends([
            'mode' => $activeMode,
        ]);

        return view('backend.user.ai-tutor.history', compact('sessions', 'activeMode'));
    }

    public function aiTutorSessionDetail(Request $request, AiChatSession $session)
    {
        abort_unless($session->user_id === Auth::id(), 403);

        $session->load([
            'course',
            'lecture',
            'messages.citations.document',
            'messages.citations.chunk',
        ]);

        $historyMode = $request->string('mode')->toString();
        if (! in_array($historyMode, ['website', 'lesson'], true)) {
            $historyMode = $session->mode === 'website' ? 'website' : 'lesson';
        }

        return view('backend.user.ai-tutor.show', compact('session', 'historyMode'));
    }
}
