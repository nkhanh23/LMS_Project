<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\LectureDiscussion;
use App\Models\Order;
use App\Repositories\LectureDiscussionRepository;

class LectureDiscussionService
{
    protected $discussionRepository;

    public function __construct(LectureDiscussionRepository $discussionRepository)
    {
        $this->discussionRepository = $discussionRepository;
    }

    public function store(array $data, int $userId): array
    {
        $lecture = CourseLecture::findOrFail($data['lecture_id']);

        if ((int) $lecture->course_id !== (int) $data['course_id']) {
            return [
                'status' => 'error',
                'code' => 422,
                'message' => 'Bài giảng không thuộc khóa học này',
            ];
        }

        $hasPurchased = Order::where('user_id', $userId)
            ->where('course_id', $data['course_id'])
            ->exists();

        if (! $hasPurchased) {
            return [
                'status' => 'error',
                'code' => 403,
                'message' => 'Bạn cần mua khóa học trước khi đặt câu hỏi',
            ];
        }

        $parentId = $data['parent_id'] ?? null;

        if ($parentId) {
            $parent = LectureDiscussion::findOrFail($parentId);

            if ((int) $parent->lecture_id !== (int) $data['lecture_id']) {
                return [
                    'status' => 'error',
                    'code' => 422,
                    'message' => 'Phản hồi không thuộc bài giảng này',
                ];
            }

            if ((int) $parent->course_id !== (int) $data['course_id']) {
                return [
                    'status' => 'error',
                    'code' => 422,
                    'message' => 'Phản hồi không thuộc khóa học này',
                ];
            }
        }

        $discussion = $this->discussionRepository->create([
            'course_id' => $data['course_id'],
            'lecture_id' => $data['lecture_id'],
            'user_id' => $userId,
            'parent_id' => $parentId,
            'content' => $data['content'],
            'is_approved' => 1,
        ]);

        $discussion->load(['user', 'parent']);

        // Gửi thông báo cho chủ câu hỏi gốc khi có phản hồi
        if ($parentId) {
            $this->notifyDiscussionOwner($discussion, $userId);
        }

        return [
            'status' => 'success',
            'code' => 201,
            'message' => $parentId ? 'Đã gửi phản hồi' : 'Câu hỏi của bạn đã được gửi',
            'discussion' => $discussion,
        ];
    }

    /**
     * Gửi thông báo cho chủ câu hỏi gốc khi có phản hồi.
     */
    protected function notifyDiscussionOwner(LectureDiscussion $reply, int $replierId): void
    {
        try {
            $parent = $reply->parent;

            if (!$parent || (int) $parent->user_id === $replierId) {
                return; // Không tự thông báo cho chính mình
            }

            $parentOwner = $parent->user;
            $replier = $reply->user;
            $lecture = CourseLecture::find($reply->lecture_id);

            if (!$parentOwner || !$replier || !$lecture) {
                return;
            }

            $parentOwner->notify(
                new \App\Notifications\DiscussionRepliedNotification($reply, $replier, $lecture)
            );
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Lỗi gửi thông báo phản hồi thảo luận: ' . $e->getMessage());
        }
    }

    public function getByLecture(int $lectureId): array
    {
        $items = $this->discussionRepository->getByLecture($lectureId, 10);

        return $items->through(function ($discussion) {
            return [
                'id' => $discussion->id,
                'content' => $discussion->content,
                'created_at' => $discussion->created_at?->format('d/m/Y H:i'),
                'created_at_human' => $discussion->created_at?->diffForHumans(),
                'user' => [
                    'id' => $discussion->user->id,
                    'name' => $discussion->user->name,
                ],
            ];
        })->items();
    }

    public function destroy(int $discussionId, int $userId): array
    {
        $discussion = $this->discussionRepository->findById($discussionId);

        if (! $discussion) {
            return [
                'status' => 'error',
                'code' => 404,
                'message' => 'Bình luận không tồn tại hoặc đã bị xóa',
            ];
        }

        if ((int) $discussion->user_id !== (int) $userId) {
            return [
                'status' => 'error',
                'code' => 403,
                'message' => 'Bạn không có quyền xóa bình luận này',
            ];
        }

        $this->discussionRepository->delete($discussion);

        return [
            'status' => 'success',
            'code' => 200,
            'message' => 'Đã xóa bình luận thành công',
            'discussion_id' => $discussionId,
        ];
    }
}
