<?php

namespace App\Repositories;

use App\Models\CourseReviews;

class CourseReviewRepository
{
    public function hasUserReviewed(int $courseId, int $userId): bool
    {
        return CourseReviews::where('course_id', $courseId)
            ->where('user_id', $userId)
            ->exists();
    }

    public function create(array $data): CourseReviews
    {
        return CourseReviews::create($data);
    }

    public function getAverageRating(int $courseId): float
    {
        return (float) (CourseReviews::where('course_id', $courseId)
            ->where('is_approved', true)
            ->avg('rating') ?? 0);
    }

    public function getRatingCount(int $courseId): int
    {
        return CourseReviews::where('course_id', $courseId)
            ->where('is_approved', true)
            ->count();
    }

    public function getRatingBreakdown(int $courseId): array
    {
        return CourseReviews::selectRaw('rating, COUNT(*) as total')
            ->where('course_id', $courseId)
            ->where('is_approved', true)
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();
    }
}
