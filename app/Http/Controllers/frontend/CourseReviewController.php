<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\CourseReviewsRequest;
use App\Models\Course;
use App\Models\CourseReviews;
use App\Models\Order;
use App\Services\CourseReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CourseReviewController extends Controller
{
    protected $courseReviewService;

    public function __construct(CourseReviewService $courseReviewService)
    {
        $this->courseReviewService = $courseReviewService;
    }

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

        $result = $this->courseReviewService->storeReview(
            $request->validated(),
            $slug,
            Auth::id()
        );

        if ($result['status'] === 'error') {
            return response()->json([
                'status' => 'error',
                'message' => $result['message']
            ], $result['code']);
        }

        $reviewHtml = view('frontend.pages.course-details.partials.review-item', [
            'review' => $result['review']
        ])->render();

        $studentFeedbackHtml = view('frontend.pages.course-details.student-feedback', [
            'ratingAverage' => $result['ratingAverage'],
            'ratingCount' => $result['ratingCount'],
            'ratingBreakdown' => $result['ratingBreakdown'],
            'ratingPercent' => $result['ratingPercent'],
        ])->render();

        $heroTitleHtml = view('frontend.pages.course-details.hero-title', [
            'course' => $result['course'],
            'ratingAverage' => $result['ratingAverage'],
            'ratingCount' => $result['ratingCount'],
        ])->render();

        return response()->json([
            'status' => 'success',
            'message' => $result['message'],
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
