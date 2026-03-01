<?php

namespace App\Service;

use App\Models\CourseGoal;
use App\Models\User;
use App\Repositories\CourseRepository;

class CourseService
{
    protected $courseRepository;
    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function createCourse(array $data, $photo = null)
    {
        return $this->courseRepository->createCourse($data, $photo);
    }

    public function updateCourse(array $data, $photo = null, $id)
    {
        return $this->courseRepository->updateCourse($data, $photo, $id);
    }

    public function createCourseGoals($courseId, array $goals)
    {
        return $this->courseRepository->createCourseGoals($courseId, $goals);
    }

    public function updateCourseGoals($courseId, array $goals)
    {
        return $this->courseRepository->updateCourseGoals($courseId, $goals);
    }
}
