<?php

namespace App\Services;

use App\Repositories\LearnerAssistantRepository;
use Illuminate\Support\Str;

class QuizEntityResolverService
{
    public function __construct(
        protected LearnerAssistantRepository $learnerAssistantRepository
    ) {}

    public function resolveForUser(
        int $userId,
        ?string $quizName,
        ?int $courseId = null,
        ?int $contextQuizId = null
    ): array {
        // lấy tất cả các bài quiz của user
        $quizzes = $this->learnerAssistantRepository->getUserQuizCandidates($userId, $courseId)
            ->map(function ($quiz) {
                $course = $quiz->course ?? $quiz->lecture?->course;

                return [
                    'quiz_id' => (int) $quiz->id,
                    'quiz_name' => $quiz->title ?? ('Quiz #' . $quiz->id),
                    'course_id' => $course?->id ? (int) $course->id : null,
                    'course_name' => $course?->course_name ?? $course?->course_title,
                ];
            })
            ->filter()
            ->values();

        // Nếu không có quiz_name thì kiểm tra contextQuizId
        if ($quizName === null || trim($quizName) === '') {
            //Kiểm tra xem Backend có nhồi ngữ cảnh (Context ID) vào không?
            if ($contextQuizId !== null) {
                $contextQuiz = $quizzes->firstWhere('quiz_id', $contextQuizId);

                if ($contextQuiz) {
                    return [
                        'status' => 'resolved',
                        'quiz_id' => $contextQuiz['quiz_id'],
                        'matched_quiz_name' => $contextQuiz['quiz_name'],
                        'course_id' => $contextQuiz['course_id'],
                        'matched_course_name' => $contextQuiz['course_name'],
                        'match_score' => 1.0,
                        'match_type' => 'conversation_context',
                    ];
                }
            }

            return [
                'status' => 'missing',
            ];
        }

        $normalizedNeedle = $this->normalizeText($quizName);

        $matches = $quizzes
            ->map(function ($quiz) use ($normalizedNeedle) {
                $normalizedQuizName = $this->normalizeText($quiz['quiz_name']);
                $score = $this->calculateScore($normalizedNeedle, $normalizedQuizName);

                return array_merge($quiz, [
                    'match_score' => $score,
                    'match_type' => $score >= 0.999 ? 'exact' : 'fuzzy',
                ]);
            })
            ->filter(fn($quiz) => $quiz['match_score'] >= 0.45)
            ->sortByDesc('match_score')
            ->values();

        if ($matches->isEmpty()) {
            return [
                'status' => 'not_found',
            ];
        }

        if ($matches->count() === 1) {
            $match = $matches->first();

            return [
                'status' => 'resolved',
                'quiz_id' => $match['quiz_id'],
                'matched_quiz_name' => $match['quiz_name'],
                'course_id' => $match['course_id'],
                'matched_course_name' => $match['course_name'],
                'match_score' => $match['match_score'],
                'match_type' => $match['match_type'],
            ];
        }

        $topMatch = $matches[0];
        $secondMatch = $matches[1];

        if ($topMatch['match_score'] >= 0.75 && ($topMatch['match_score'] - $secondMatch['match_score']) >= 0.08) {
            return [
                'status' => 'resolved',
                'quiz_id' => $topMatch['quiz_id'],
                'matched_quiz_name' => $topMatch['quiz_name'],
                'course_id' => $topMatch['course_id'],
                'matched_course_name' => $topMatch['course_name'],
                'match_score' => $topMatch['match_score'],
                'match_type' => $topMatch['match_type'],
            ];
        }

        return [
            'status' => 'ambiguous',
            'candidates' => $matches
                ->take(3)
                ->map(fn($match) => [
                    'quiz_id' => $match['quiz_id'],
                    'quiz_name' => $match['quiz_name'],
                    'course_name' => $match['course_name'],
                    'match_score' => $match['match_score'],
                ])
                ->all(),
        ];
    }

    protected function normalizeText(string $value): string
    {
        $ascii = Str::ascii(mb_strtolower(trim($value)));
        $collapsed = preg_replace('/[^a-z0-9]+/i', ' ', $ascii) ?? $ascii;
        return trim(preg_replace('/\s+/', ' ', $collapsed) ?? $collapsed);
    }

    protected function calculateScore(string $needle, string $haystack): float
    {
        if ($needle === '' || $haystack === '') {
            return 0.0;
        }

        if ($needle === $haystack) {
            return 1.0;
        }

        if (str_contains($haystack, $needle) || str_contains($needle, $haystack)) {
            return 0.9;
        }

        similar_text($needle, $haystack, $percent);

        return round($percent / 100, 4);
    }
}
