<?php

namespace App\Services;

use App\Models\CourseGoal;
use App\Models\User;
use App\Repositories\CourseRepository;
use Illuminate\Support\Facades\Auth;

class CourseService
{
    protected $courseRepository;
    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function createCourse(array $data, $photo = null)
    {
        // Không tin dữ liệu status/approval gửi từ form
        unset(
            $data['status'],
            $data['approval_status'],
            $data['approval_note'],
            $data['submitted_for_review_at'],
            $data['approved_at'],
            $data['reviewed_by'],
            $data['reviewed_at']
        );

        // Gắn business state mặc định cho course mới
        $data['instructor_id'] = Auth::id();
        $data['status'] = 0; // chưa public
        $data['approval_status'] = 'draft';
        $data['approval_note'] = null;
        $data['submitted_for_review_at'] = null;
        $data['approved_at'] = null;
        $data['reviewed_by'] = null;
        $data['reviewed_at'] = null;

        return $this->courseRepository->createCourse($data, $photo);
    }

    public function updateCourse(array $data, $photo = null, int $id)
    {
        $course = $this->courseRepository->findCourseByIdAndInstructor($id, Auth::id());

        // Không cho form tự điều khiển approval
        unset(
            $data['status'],
            $data['approval_status'],
            $data['approval_note'],
            $data['submitted_for_review_at'],
            $data['approved_at'],
            $data['reviewed_by'],
            $data['reviewed_at'],
            $data['instructor_id']
        );

        // Giữ approval state theo business rule
        if (in_array($course->approval_status, ['draft', 'rejected'], true)) {
            $data['status'] = 0; // chưa public
        }

        // published thì tạm giữ nguyên
        if ($course->approval_status === 'published') {
            $data['status'] = $course->status;
        }

        return $this->courseRepository->updateCourse($course, $data, $photo);
    }

    public function createCourseGoals($courseId, array $goals)
    {
        return $this->courseRepository->createCourseGoals($courseId, $goals);
    }

    public function updateCourseGoals($courseId, array $goals)
    {
        return $this->courseRepository->updateCourseGoals($courseId, $goals);
    }

    public function getCourses($search = null, $categoryId = null, $subCategoryId = null, $instructorId = null, $perPage = 5, $minAmount = null, $maxAmount = null)
    {
        return $this->courseRepository->getCourses($search, $categoryId, $subCategoryId, $instructorId, $perPage, $minAmount, $maxAmount);
    }
}
