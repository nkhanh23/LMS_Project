<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseReviews;
use App\Models\Order;
use App\Repositories\CourseReviewRepository;

class CourseReviewService
{
    protected $courseReviewRepository;

    public function __construct(CourseReviewRepository $courseReviewRepository)
    {
        $this->courseReviewRepository = $courseReviewRepository;
    }

    public function storeReview(array $data, string $slug, int $userId): array
    {
        $course = Course::where('course_name_slug', $slug)->firstOrFail();

        if ((int) $data['course_id'] !== (int) $course->id) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'Khóa học không hợp lệ',
            ];
        }

        $hasPurchased = Order::where('user_id', $userId)
            ->where('course_id', $course->id)
            ->exists();

        if (! $hasPurchased) {
            return [
                'status' => 'error',
                'code' => 403,
                'message' => 'Bạn cần mua khóa học trước khi đánh giá',
            ];
        }

        $alreadyReviewed = $this->courseReviewRepository->hasUserReviewed($course->id, $userId);

        if ($alreadyReviewed) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'Bạn đã đánh giá khóa học này rồi',
            ];
        }

        $review = $this->courseReviewRepository->create([
            'course_id'     => $course->id,
            'user_id'       => $userId,
            'instructor_id' => $course->instructor_id,
            'rating'        => $data['rating'],
            'comment'       => $data['comment'],
            'is_approved'   => 1,
        ]);

        $review->load('user');

        $ratingAverage = round(
            $this->courseReviewRepository->getAverageRating($course->id),
            1
        );

        $ratingCount = $this->courseReviewRepository->getRatingCount($course->id);
        $ratingBreakdown = $this->courseReviewRepository->getRatingBreakdown($course->id);

        $ratingPercent = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratingBreakdown[$i] ?? 0;
            $ratingPercent[$i] = $ratingCount > 0 ? round(($count / $ratingCount) * 100) : 0;
        }

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Đánh giá của bạn đã được gửi thành công',
            'course' => $course,
            'review' => $review,
            'ratingAverage' => $ratingAverage,
            'ratingCount' => $ratingCount,
            'ratingBreakdown' => $ratingBreakdown,
            'ratingPercent' => $ratingPercent,
        ];
    }
}
