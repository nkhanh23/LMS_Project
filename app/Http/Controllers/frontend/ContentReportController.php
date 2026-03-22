<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContentReportRequest;
use App\Models\CourseReviews;
use App\Models\LectureDiscussion;
use App\Services\ModerationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class ContentReportController extends Controller
{
    protected $moderationService;

    public function __construct(ModerationService $moderationService)
    {
        $this->moderationService = $moderationService;
    }

    public function storeReview(StoreContentReportRequest $request, CourseReviews $review): JsonResponse
    {
        try {
            $this->moderationService->createReviewReport(
                $request->validated(),
                $review,
                Auth::id()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Đã gửi báo cáo review thành công.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);
        }
    }

    public function storeDiscussion(StoreContentReportRequest $request, LectureDiscussion $discussion): JsonResponse
    {
        try {
            $this->moderationService->createDiscussionReport(
                $request->validated(),
                $discussion,
                Auth::id()
            );

            return response()->json([
                'status' => 'success',
                'message' => 'Đã gửi báo cáo thảo luận thành công.',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'message' => collect($e->errors())->flatten()->first(),
            ], 422);
        }
    }
}
