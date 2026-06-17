<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class QuizAttempController extends Controller
{
    public function submit(Request $request, Quiz $quiz)
    {
        $quiz->load('lecture', 'questions.options');


        $user = Auth::user();

        if (!$user) {
            return redirect()->back()->with('error', 'Bạn cần đăng nhập để làm quiz.');
        }

        $hasAccess = $user->hasAccessToQuiz($quiz); // kiểm tra student đã mua hoặc enroll course chưa
        if (!$hasAccess) {
            return redirect()->back()->with('error', 'Bạn chưa có quyền làm quiz này.');
        }

        // Kiểm tra số lần thử tối đa
        if ($quiz->max_attempts) {
            $userAttemptsCount = QuizAttempt::where('quiz_id', $quiz->id)
                ->where('user_id', $user->id)
                ->count();
            if ($userAttemptsCount >= $quiz->max_attempts) {
                return redirect()->back()->with('error', 'Bạn đã hết số lần làm bài cho quiz này.');
            }
        }

        $answers = $request->input('answers', []);
        $totalQuestions = $quiz->questions->count();
        $correctAnswers = 0;
        $attempt = null;

        DB::transaction(function () use ($quiz, $user, $answers, $totalQuestions, &$correctAnswers, &$attempt) {
            $attempt = QuizAttempt::create([
                'quiz_id' => $quiz->id,
                'lecture_id' => $quiz->lecture_id,
                'course_id' => $quiz->course_id,
                'user_id' => $user->id,
                'score' => 0,
                'total_questions' => $totalQuestions,
                'correct_answers' => 0,
                'status' => 'submitted',
                'started_at' => now(),
                'submitted_at' => now(),
            ]);

            foreach ($quiz->questions as $question) {
                // lấy đáp án của user
                $selectedOptionId = $answers[$question->id] ?? null;
                // lấy đáp án đúng
                $correctOption = $question->options->firstWhere('is_correct', true);
                // so sánh đáp án
                $isCorrect = $correctOption && $selectedOptionId == $correctOption->id;

                // đếm số câu trả lời đúng
                if ($isCorrect) {
                    $correctAnswers++;
                }

                // save vào database
                QuizAttemptAnswer::create([
                    'attempt_id' => $attempt->id,
                    'question_id' => $question->id,
                    'selected_option_id' => $selectedOptionId,
                    'is_correct' => $isCorrect,
                ]);
            }

            $score = $totalQuestions > 0 ? round(($correctAnswers / $totalQuestions) * 100) : 0;

            $attempt->update([
                'score' => $score,
                'correct_answers' => $correctAnswers,
            ]);
        });

        if ($quiz->show_result_immediately && $attempt) {
            return redirect()->back()->with([
                'success' => 'Bạn đã nộp bài thành công.',
                'quiz_attempt_id' => $attempt->id
            ]);
        }

        return redirect()->back()->with('success', 'Bạn đã nộp bài thành công.');
    }
}
