<?php

namespace App\Repositories;

use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\LectureDiscussion;

class LectureDiscussionRepository
{
    /**
     * FRONTEND
     */
    public function getByLecture(int $lectureId, int $perPage = 10)
    {
        return LectureDiscussion::with(['user', 'replies'])
            ->where('lecture_id', $lectureId)
            ->whereNull('parent_id')
            ->latest()
            ->paginate($perPage);
    }

    public function create(array $data): LectureDiscussion
    {
        return LectureDiscussion::create($data);
    }

    public function findById(int $id): ?LectureDiscussion
    {
        return LectureDiscussion::with(['user', 'parent'])->find($id);
    }

    public function findRootById(int $id): ?LectureDiscussion
    {
        return LectureDiscussion::whereNull('parent_id')->find($id);
    }

    public function findByIdWithTrashed(int $id): ?LectureDiscussion
    {
        return LectureDiscussion::withTrashed()->find($id);
    }

    public function delete(LectureDiscussion $discussion): bool
    {
        return (bool) $discussion->delete();
    }

    public function restore(LectureDiscussion $discussion): bool
    {
        return (bool) $discussion->restore();
    }

    public function forceDelete(LectureDiscussion $discussion): bool
    {
        return (bool) $discussion->forceDelete();
    }

    /**
     * BACKEND INSTRUCTOR
     */

    //Lấy danh sách thảo luận
    public function getInstructorDiscussionQuery(int $instructorId, array $filters = [])
    {
        $query = LectureDiscussion::query()
            ->with([
                'user:id,name,email',
                'course:id,course_name,instructor_id',
                'lecture:id,course_id,section_id,lecture_title',
                'parent:id,content,user_id',
            ])
            ->whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            });

        if (!empty($filters['course_id'])) {
            $query->where('course_id', $filters['course_id']);
        }

        if (!empty($filters['lecture_id'])) {
            $query->where('lecture_id', $filters['lecture_id']);
        }

        if (isset($filters['is_approved']) && $filters['is_approved'] !== '') {
            $query->where('is_approved', $filters['is_approved']);
        }

        if (!empty($filters['keyword'])) {
            $keyword = trim($filters['keyword']);
            $query->where('content', 'like', "%{$keyword}%");
        }

        if (!empty($filters['parent_type'])) {
            if ($filters['parent_type'] === 'root') {
                $query->whereNull('parent_id');
            }

            if ($filters['parent_type'] === 'reply') {
                $query->whereNotNull('parent_id');
            }
        }

        return $query->latest();
    }

    //Phân trang danh sách thảo luận
    public function paginateInstructorDiscussions(int $instructorId, array $filters = [], int $perPage = 15)
    {
        return $this->getInstructorDiscussionQuery($instructorId, $filters)
            ->paginate($perPage)
            ->appends($filters);
    }

    //Tìm thảo luận theo id
    public function findByIdForInstructor(int $discussionId, int $instructorId): ?LectureDiscussion
    {
        return LectureDiscussion::query()
            ->with([
                'user:id,name,email,photo',
                'course:id,course_name,instructor_id',
                'lecture:id,course_id,section_id,lecture_title',
                'parent.user:id,name,email,photo',
                'replies' => function ($query) {
                    $query->with([
                        'user:id,name,email,photo',
                    ])->orderBy('created_at', 'asc');
                },
            ])
            ->where('id', $discussionId)
            ->whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })
            ->first();
    }

    //Duyệt thảo luận
    public function approve(LectureDiscussion $discussion): bool
    {
        return $discussion->update([
            'is_approved' => 1,
        ]);
    }

    //Bỏ duyệt thảo luận
    public function unapprove(LectureDiscussion $discussion): bool
    {
        return $discussion->update([
            'is_approved' => 0,
        ]);
    }

    //Xóa mềm thảo luận
    public function softDelete(LectureDiscussion $discussion): bool
    {
        return (bool) $discussion->delete();
    }

    //Tạo trả lời
    public function createReply(array $data): LectureDiscussion
    {
        return LectureDiscussion::create($data);
    }

    //Lấy danh sách khóa học của instructor
    public function getInstructorCourses(int $instructorId)
    {
        return Course::query()
            ->where('instructor_id', $instructorId)
            ->select('id', 'course_name')
            ->orderBy('course_name')
            ->get();
    }

    //Lấy danh sách bài học của instructor theo khóa học
    public function getInstructorLecturesByCourse(int $instructorId, ?int $courseId = null)
    {
        $query = CourseLecture::query()
            ->select('id', 'lecture_title', 'course_id')
            ->whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            });

        if ($courseId) {
            $query->where('course_id', $courseId);
        }

        return $query->orderBy('lecture_title')->get();
    }

    //Lấy danh sách bài học của instructor theo khóa học
    public function getInstructorLecturesBySelectedCourse(int $instructorId, int $courseId)
    {
        return CourseLecture::query()
            ->select('id', 'lecture_title', 'course_id')
            ->where('course_id', $courseId)
            ->whereHas('course', function ($q) use ($instructorId) {
                $q->where('instructor_id', $instructorId);
            })
            ->orderBy('lecture_title')
            ->get();
    }
}
