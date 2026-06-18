<?php

namespace App\Services;

use App\Repositories\LearnerAssistantRepository;
use Illuminate\Support\Str;

class CourseEntityResolverService
{
    public function __construct(
        protected LearnerAssistantRepository $learnerAssistantRepository
    ) {}

    public function resolveForUser(int $userId, ?string $courseName, ?int $contextCourseId = null): array
    {
        $courses = $this->learnerAssistantRepository->getUserCourseCandidates($userId)
            ->map(function ($enrollment) {
                $course = $enrollment->course;

                if (! $course) {
                    return null;
                }

                return [
                    'course_id' => (int) $course->id,
                    'course_name' => $course->course_name ?? $course->course_title ?? ('Course #' . $course->id),
                    'course_slug' => $course->course_name_slug,
                ];
            })
            ->filter()
            ->values();

        if ($courseName === null || trim($courseName) === '') {
            if ($contextCourseId !== null) {
                $contextCourse = $courses->firstWhere('course_id', $contextCourseId);

                if ($contextCourse) {
                    return [
                        'status' => 'resolved',
                        'course_id' => $contextCourse['course_id'],
                        'matched_course_name' => $contextCourse['course_name'],
                        'match_score' => 1.0,
                        'match_type' => 'page_context',
                    ];
                }
            }

            return [
                'status' => 'missing',
            ];
        }

        $normalizedNeedle = $this->normalizeText($courseName);

        $matches = $courses
            ->map(function ($course) use ($normalizedNeedle) {
                $normalizedCourseName = $this->normalizeText($course['course_name']);
                $score = $this->calculateScore($normalizedNeedle, $normalizedCourseName);

                return array_merge($course, [
                    'match_score' => $score,
                    'match_type' => $score >= 0.999 ? 'exact' : 'fuzzy',
                ]);
            })
            ->filter(fn ($course) => $course['match_score'] >= 0.45)
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
                ->map(fn ($match) => [
                    'course_id' => $match['course_id'],
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
