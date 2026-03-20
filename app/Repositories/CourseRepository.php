<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\Course;
use App\Models\CourseGoal;
use App\Traits\FileUploadTrait;

class CourseRepository
{
    use FileUploadTrait;

    public function createCourse($data, $photo)
    {
        $course = new Course();
        //Xóa key course_goals khỏi $data
        unset($data['course_goals']);

        //Xử lý upload file
        if ($photo) {
            $data['course_image'] = $this->uploadFile($photo, 'course', $course->course_image);
        }
        return Course::create($data);
    }

    public function updateCourse($course, $data, $photo)
    {
        //Xóa key course_goals khỏi $data
        unset($data['course_goals']);
        if ($photo) {
            $data['course_image'] = $this->uploadFile($photo, 'course', $course->course_image);
        }
        $course->update($data);
        return $course->fresh();
    }

    public function findCourseByIdAndInstructor(int $id, int $instructorId)
    {
        return Course::where('id', $id)
            ->where('instructor_id', $instructorId)
            ->firstOrFail();
    }

    public function createCourseGoals($courseId, array $goals)
    {
        foreach ($goals as $goal) {
            if ($goal) {
                CourseGoal::create([
                    'course_id' => $courseId,
                    'goal_name' => $goal,
                ]);
            }
        }
    }

    public function updateCourseGoals($courseId, array $goals)
    {
        CourseGoal::where('course_id', $courseId)->delete();
        foreach ($goals as $goal) {
            if ($goal) {
                CourseGoal::updateOrCreate([
                    'course_id' => $courseId,
                    'goal_name' => $goal,
                ]);
            }
        }
    }

    public function getCourses($search = null, $categoryId = null, $subCategoryId = null, $instructorId = null, $perPage = 5, $minAmount = null, $maxAmount = null)
    {
        $query = Course::with(['category', 'subcategory'])->when($search, function ($query, $search) {
            return $query->where('course_name', 'like', "%{$search}%");
        })
            ->when($categoryId, function ($query, $categoryId) {
                return $query->where('category_id', $categoryId);
            })
            ->when($subCategoryId, function ($query, $subCategoryId) {
                return $query->where('sub_category_id', $subCategoryId);
            })
            ->when($instructorId, function ($query, $instructorId) {
                return $query->where('instructor_id', $instructorId);
            })
            ->when(filled($minAmount), function ($query) use ($minAmount) {
                return $query->where('selling_price', '>=', $minAmount);
            })
            ->when(filled($maxAmount), function ($query) use ($maxAmount) {
                return $query->where('selling_price', '<=', $maxAmount);
            });

        return $query->latest()->paginate($perPage);
    }
}
