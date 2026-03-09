<?php

namespace App\Services;

use App\Repositories\AdminInstructorRepository;

class AdminInstructorService
{
    protected $instructorRepository;

    public function __construct(AdminInstructorRepository $instructorRepository)
    {
        $this->instructorRepository = $instructorRepository;
    }

    public function getInstructors($search = null, $status = null, $perPage = 10)
    {
        return $this->instructorRepository->getInstructors($search, $status, $perPage);
    }
}
