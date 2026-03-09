<?php

namespace App\Services;

use App\Repositories\AdminCourseRepository;

class AdminCourseService
{
    protected $courseRepository;

    public function __construct(AdminCourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    public function getCourses($search = null, $categoryId = null, $instructorId = null, $perPage = 10)
    {
        return $this->courseRepository->getCourses($search, $categoryId, $instructorId, $perPage);
    }
}
