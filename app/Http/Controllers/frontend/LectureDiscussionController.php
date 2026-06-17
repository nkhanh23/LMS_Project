<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LectureDiscussionRequest;
use App\Models\CourseLecture;
use App\Models\LectureDiscussion;
use App\Services\LectureDiscussionService;
use App\Events\DiscussionCreated;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class LectureDiscussionController extends Controller
{
    protected $discussionService;

    public function __construct(LectureDiscussionService $discussionService)
    {
        $this->discussionService = $discussionService;
    }

    public function index(CourseLecture $lecture): JsonResponse
    {
        $discussions = $this->discussionService->getByLecture($lecture->id);

        return response()->json([
            'status' => 'success',
            'discussions' => $discussions,
        ]);
    }

    public function store(LectureDiscussionRequest $request)
    {
        //lưu bình luận vào database và trả về dữ liệu
        $result = $this->discussionService->store(
            $request->validated(),
            Auth::id()
        );

        if ($result['status'] === 'error') {
            return response()->json([
                'status' => $result['status'],
                'message' => $result['message'],
            ], $result['code']);
        }

        $discussion = $result['discussion'];

        // Chỉ render view cần thiết dựa trên việc nó là câu hỏi chính hay phản hồi
        if ($discussion->parent_id) {
            $html = view('frontend.pages.learning.partials.reply-item', [
                'discussion' => $discussion,
                'depth' => 1,
            ])->render();
        } else {
            $html = view('frontend.pages.learning.partials.discussion-item', [
                'discussion' => $discussion,
                'depth' => 0,
            ])->render();
        }

        // Bắn sự kiện realtime cho các user khác (toOthers)
        broadcast(new DiscussionCreated($discussion, $html))->toOthers();

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'discussion' => [
                'id' => $discussion->id,
                'parent_id' => $discussion->parent_id,
            ],
            'html' => $html,
        ], $result['code']);
    }

    public function destroy(LectureDiscussion $discussion): JsonResponse
    {
        $result = $this->discussionService->destroy($discussion->id, Auth::id());

        return response()->json([
            'status' => $result['status'],
            'message' => $result['message'],
            'discussion_id' => $result['discussion_id'] ?? null,
        ], $result['code']);
    }
}
