<?php

namespace App\Repositories;

use App\Models\Enrollment;
use App\Models\Quiz;
use App\Models\QuizAttempt;
use App\Models\RefundRequest;

class LearnerAssistantRepository
{
    public function getUserCourseCandidates(int $userId)
    {
        return Enrollment::query()
            ->with([
                'course',
                'courseProgress.lastLecture',
                'lastLecture',
            ])
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->latest('last_accessed_at')
            ->get();
    }

    public function getCourseProgressData(int $userId, int $courseId): ?array
    {
        $enrollment = Enrollment::query()
            ->with([
                'course',
                'courseProgress.lastLecture',
                'lastLecture',
            ])
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('status', 'active')
            ->first();

        if (! $enrollment || ! $enrollment->course) {
            return null;
        }

        $progress = $enrollment->courseProgress;
        $completionPercent = (int) ($progress->completion_percent ?? 0);
        $completedLectures = (int) ($progress->completed_lectures ?? 0);
        $totalLectures = (int) ($progress->total_lectures ?? 0);
        $lastLecture = $progress?->lastLecture ?? $enrollment->lastLecture;

        return [
            'course_id' => $enrollment->course->id,
            'course_title' => $enrollment->course->course_name ?? $enrollment->course->course_title ?? ('Course #' . $enrollment->course->id),
            'course_slug' => $enrollment->course->course_name_slug,
            'completion_percent' => $completionPercent,
            'completed_lectures' => $completedLectures,
            'total_lectures' => $totalLectures,
            'status' => $completionPercent >= 100 ? 'completed' : ($completionPercent > 0 ? 'in_progress' : 'not_started'),
            'last_activity_at' => optional($progress?->last_activity_at ?? $enrollment->last_accessed_at)->toDateTimeString(),
            'last_lecture' => $lastLecture ? [
                'id' => $lastLecture->id,
                'title' => $lastLecture->lecture_title ?? $lastLecture->title ?? ('Lesson #' . $lastLecture->id),
            ] : null,
        ];
    }

    public function getUnfinishedCoursesData(int $userId): array
    {
        $enrollments = $this->getUserCourseCandidates($userId);

        $courses = $enrollments
            ->map(function ($enrollment) {
                $course = $enrollment->course;
                $progress = $enrollment->courseProgress;

                if (! $course) {
                    return null;
                }

                $completionPercent = (int) ($progress->completion_percent ?? 0);

                if ($completionPercent >= 100) {
                    return null;
                }

                return [
                    'course_id' => $course->id,
                    'title' => $course->course_name ?? $course->course_title ?? ('Course #' . $course->id),
                    'slug' => $course->course_name_slug,
                    'completion_percent' => $completionPercent,
                    'status' => $completionPercent > 0 ? 'in_progress' : 'not_started',
                    'last_activity_at' => optional($progress?->last_activity_at ?? $enrollment->last_accessed_at)->toDateTimeString(),
                ];
            })
            ->filter()
            ->values();

        return [
            'summary' => [
                'total_unfinished' => $courses->count(),
                'not_started_count' => $courses->where('status', 'not_started')->count(),
                'in_progress_count' => $courses->where('status', 'in_progress')->count(),
            ],
            'courses' => $courses->all(),
        ];
    }

    public function getUserQuizCandidates(int $userId, ?int $courseId = null)
    {
        $query = Quiz::query()
            ->with([
                'course',
                'lecture',
            ])
            ->withCount([
                'attempts as user_attempts_count' => function ($builder) use ($userId) {
                    $builder->where('user_id', $userId);
                },
            ])
            ->whereHas('attempts', function ($builder) use ($userId) {
                $builder->where('user_id', $userId);
            });

        if ($courseId !== null) {
            $query->where('course_id', $courseId);
        }

        return $query
            ->latest('id')
            ->get();
    }

    public function getQuizHistoryData(
        int $userId,
        ?int $courseId = null,
        ?int $quizId = null,
        int $limit = 5
    ): array
    {
        $query = QuizAttempt::query()
            ->with([
                'quiz',
                'lecture.course',
                'course',
            ])
            ->where('user_id', $userId);

        if ($courseId !== null) {
            $query->where('course_id', $courseId);
        }

        if ($quizId !== null) {
            $query->where('quiz_id', $quizId);
        }

        $attempts = $query
            ->orderByDesc('submitted_at')
            ->orderByDesc('id')
            ->get();

        $recentAttempts = $attempts
            ->take($limit)
            ->map(function ($attempt) {
                $course = $attempt->course ?? $attempt->lecture?->course;

                return [
                    'attempt_id' => $attempt->id,
                    'quiz_title' => $attempt->quiz?->title ?? ('Quiz #' . $attempt->quiz_id),
                    'course_title' => $course?->course_name ?? $course?->course_title ?? null,
                    'lecture_title' => $attempt->lecture?->lecture_title ?? $attempt->lecture?->title,
                    'score' => (int) ($attempt->score ?? 0),
                    'submitted_at' => optional($attempt->submitted_at)->toDateTimeString(),
                ];
            })
            ->values();

        $selectedQuiz = null;
        if ($quizId !== null) {
            $quiz = Quiz::query()
                ->with([
                    'course',
                    'lecture',
                ])
                ->withCount([
                    'attempts as user_attempts_count' => function ($builder) use ($userId) {
                        $builder->where('user_id', $userId);
                    },
                ])
                ->find($quizId);

            if ($quiz) {
                $maxAttempts = $quiz->max_attempts !== null ? (int) $quiz->max_attempts : null;
                $attemptsCount = (int) ($quiz->user_attempts_count ?? 0);
                $course = $quiz->course ?? $quiz->lecture?->course;

                $selectedQuiz = [
                    'quiz_id' => $quiz->id,
                    'quiz_title' => $quiz->title ?? ('Quiz #' . $quiz->id),
                    'course_title' => $course?->course_name ?? $course?->course_title,
                    'lecture_title' => $quiz->lecture?->lecture_title ?? $quiz->lecture?->title,
                    'max_attempts' => $maxAttempts,
                    'attempts_used' => $attemptsCount,
                    'remaining_attempts' => $maxAttempts !== null ? max(0, $maxAttempts - $attemptsCount) : null,
                    'latest_score' => $attempts->first() ? (int) ($attempts->first()->score ?? 0) : null,
                    'latest_submitted_at' => optional($attempts->first()?->submitted_at)->toDateTimeString(),
                ];
            }
        }

        return [
            'summary' => [
                'total_attempts' => $attempts->count(),
                'latest_attempt_at' => optional($attempts->first()?->submitted_at)->toDateTimeString(),
            ],
            'selected_quiz' => $selectedQuiz,
            'recent_attempts' => $recentAttempts->all(),
        ];
    }

    public function getCertificateStatusData(int $userId, ?int $courseId = null): array
    {
        $query = Enrollment::query()
            ->with([
                'course',
                'courseProgress',
            ])
            ->where('user_id', $userId)
            ->where('status', 'active')
            ->whereHas('course', function ($builder) {
                $builder->whereIn('certificate', ['yes', 'Có', 'co']);
            });

        if ($courseId !== null) {
            $query->where('course_id', $courseId);
        }

        $enrollments = $query->get();

        $courses = $enrollments
            ->map(function ($enrollment) {
                $course = $enrollment->course;
                $progress = $enrollment->courseProgress;

                if (! $course) {
                    return null;
                }

                $completionPercent = (int) ($progress->completion_percent ?? 0);

                return [
                    'course_id' => $course->id,
                    'title' => $course->course_name ?? $course->course_title ?? ('Course #' . $course->id),
                    'certificate_enabled' => true,
                    'is_eligible' => $completionPercent >= 100,
                    'completion_percent' => $completionPercent,
                    'completed_at' => optional($progress?->completed_at ?? $enrollment->completed_at)->toDateTimeString(),
                ];
            })
            ->filter()
            ->values();

        return [
            'summary' => [
                'eligible_courses_count' => $courses->where('is_eligible', true)->count(),
                'certificate_courses_count' => $courses->count(),
            ],
            'courses' => $courses->all(),
        ];
    }

    public function getRefundStatusData(
        int $userId,
        ?int $courseId = null,
        ?string $orderReference = null,
        int $limit = 5
    ): array {
        $query = RefundRequest::query()
            ->with([
                'order.course',
                'payment',
            ])
            ->where('user_id', $userId);

        if ($courseId !== null) {
            $query->whereHas('order', function ($builder) use ($courseId) {
                $builder->where('course_id', $courseId);
            });
        }

        if ($orderReference !== null && trim($orderReference) !== '') {
            $normalizedOrderReference = trim($orderReference);

            if (ctype_digit($normalizedOrderReference)) {
                $query->where('order_id', (int) $normalizedOrderReference);
            }
        }

        $requests = $query
            ->latest('requested_at')
            ->latest('id')
            ->get();

        $recentRequests = $requests
            ->take($limit)
            ->map(function ($request) {
                return [
                    'refund_request_id' => $request->id,
                    'order_id' => $request->order_id,
                    'course_title' => $request->order?->course?->course_name ?? $request->order?->course?->course_title,
                    'type' => $request->type,
                    'status' => $request->status,
                    'requested_amount' => (float) ($request->requested_amount ?? 0),
                    'approved_amount' => $request->approved_amount !== null ? (float) $request->approved_amount : null,
                    'requested_at' => optional($request->requested_at)->toDateTimeString(),
                    'reviewed_at' => optional($request->reviewed_at)->toDateTimeString(),
                ];
            })
            ->values();

        return [
            'summary' => [
                'open_requests_count' => $requests->whereIn('status', ['pending', 'approved'])->count(),
                'processed_requests_count' => $requests->where('status', 'processed')->count(),
                'rejected_requests_count' => $requests->where('status', 'rejected')->count(),
            ],
            'recent_requests' => $recentRequests->all(),
        ];
    }
}
