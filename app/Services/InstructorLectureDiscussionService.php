<?php

namespace App\Services;

use App\Repositories\LectureDiscussionRepository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class InstructorLectureDiscussionService
{
    protected $lectureDiscussionRepository;
    public function __construct(LectureDiscussionRepository $lectureDiscussionRepository)
    {
        $this->lectureDiscussionRepository = $lectureDiscussionRepository;
    }

    //Lấy danh sách thảo luận
    public function getDiscussionList(int $instructorId, array $filters = [])
    {
        return $this->lectureDiscussionRepository
            ->paginateInstructorDiscussions($instructorId, $filters, 15);
    }

    //Lấy chi tiết thảo luận
    public function getDiscussionDetail(int $discussionId, int $instructorId)
    {
        $discussion = $this->lectureDiscussionRepository
            ->findByIdForInstructor($discussionId, $instructorId);

        if (!$discussion) {
            throw new ModelNotFoundException('Discussion not found.');
        }

        return $discussion;
    }

    //Duyệt thảo luận
    public function approveDiscussion(int $discussionId, int $instructorId): bool
    {
        $discussion = $this->getDiscussionDetail($discussionId, $instructorId);

        return $this->lectureDiscussionRepository->approve($discussion);
    }

    //Bỏ duyệt thảo luận
    public function unapproveDiscussion(int $discussionId, int $instructorId): bool
    {
        $discussion = $this->getDiscussionDetail($discussionId, $instructorId);

        return $this->lectureDiscussionRepository->unapprove($discussion);
    }

    //Xóa mềm thảo luận
    public function deleteDiscussion(int $discussionId, int $instructorId): bool
    {
        $discussion = $this->getDiscussionDetail($discussionId, $instructorId);

        return $this->lectureDiscussionRepository->softDelete($discussion);
    }

    //Tạo trả lời
    public function replyDiscussion(int $discussionId, int $instructorId, array $data)
    {
        $discussion = $this->getDiscussionDetail($discussionId, $instructorId);

        // Nếu đang mở reply thì reply vào parent của nó
        // Nếu đang mở root thì reply vào chính nó
        $targetParent = $discussion->parent_id ? $discussion->parent : $discussion;

        return DB::transaction(function () use ($targetParent, $data) {
            return $this->lectureDiscussionRepository->createReply([
                'course_id'   => $targetParent->course_id,
                'lecture_id'  => $targetParent->lecture_id,
                'user_id'     => auth()->id(),
                'parent_id'   => $targetParent->id,
                'content'     => $data['content'],
                'is_approved' => 1,
            ]);
        });
    }

    //Lấy dữ liệu lọc
    public function getFilterData(int $instructorId, ?int $courseId = null): array
    {
        return [
            'courses'  => $this->lectureDiscussionRepository->getInstructorCourses($instructorId),
            'lectures' => $this->lectureDiscussionRepository->getInstructorLecturesByCourse($instructorId, $courseId),
        ];
    }

    //Lấy danh sách bài học theo khóa học
    public function getLecturesByCourse(int $instructorId, int $courseId)
    {
        return $this->lectureDiscussionRepository
            ->getInstructorLecturesBySelectedCourse($instructorId, $courseId);
    }
}
