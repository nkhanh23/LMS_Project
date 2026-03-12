<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseReviewsRequest;
use App\Models\Course;
use App\Models\CourseReviews;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseReviewsRequest $request, $slug)
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vui lòng đăng nhập để gửi đánh giá'
            ], 401);
        }

        $course = Course::where('course_name_slug', $slug)->firstOrFail();

        if ((int) $request->course_id !== (int) $course->id) {
            return response()->json([
                'status' => 'error',
                'message' => 'Khóa học không hợp lệ'
            ], 422);
        }

        $hasPurchased = Order::where('user_id', Auth::id())
            ->where('course_id', $course->id)
            ->exists();

        if (!$hasPurchased) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn cần mua khóa học trước khi đánh giá'
            ], 403);
        }

        $alreadyReviewed = CourseReviews::where('course_id', $course->id)
            ->where('user_id', Auth::id())
            ->exists();

        if ($alreadyReviewed) {
            return response()->json([
                'status' => 'error',
                'message' => 'Bạn đã đánh giá khóa học này rồi'
            ], 422);
        }

        $review = CourseReviews::create([
            'course_id'     => $course->id,
            'user_id'       => Auth::id(),
            'instructor_id' => $course->instructor_id,
            'rating'        => $request->rating,
            'comment'       => $request->comment,
            'is_approved'   => 1,
        ]);

        $review->load('user');

        $ratingAverage = round(
            CourseReviews::where('course_id', $course->id)
                ->where('is_approved', true)
                ->avg('rating') ?? 0,
            1
        );

        $ratingCount = CourseReviews::where('course_id', $course->id)
            ->where('is_approved', true)
            ->count();

        $ratingBreakdown = CourseReviews::selectRaw('rating, COUNT(*) as total')
            ->where('course_id', $course->id)
            ->where('is_approved', true)
            ->groupBy('rating')
            ->pluck('total', 'rating')
            ->toArray();

        $ratingPercent = [];
        for ($i = 1; $i <= 5; $i++) {
            $count = $ratingBreakdown[$i] ?? 0;
            $ratingPercent[$i] = $ratingCount > 0 ? round(($count / $ratingCount) * 100) : 0;
        }

        $reviewHtml = view('frontend.pages.course-details.partials.review-item', [
            'review' => $review
        ])->render();

        $studentFeedbackHtml = view('frontend.pages.course-details.student-feedback', [
            'ratingAverage' => $ratingAverage,
            'ratingCount' => $ratingCount,
            'ratingBreakdown' => $ratingBreakdown,
            'ratingPercent' => $ratingPercent,
        ])->render();

        $heroTitleHtml = view('frontend.pages.course-details.hero-title', [
            'course' => $course,
            'ratingAverage' => $ratingAverage,
            'ratingCount' => $ratingCount,
        ])->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Đánh giá của bạn đã được gửi thành công',
            'review_html' => $reviewHtml,
            'student_feedback_html' => $studentFeedbackHtml,
            'hero_title_html' => $heroTitleHtml,
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
