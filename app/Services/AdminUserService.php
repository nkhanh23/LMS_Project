<?php

namespace App\Services;

use App\Repositories\AdminUserRepository;

class AdminUserService
{
    protected $userRepository;

    public function __construct(AdminUserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getUsers($search = null, $status = null, $perPage = 10)
    {
        return $this->userRepository->getUsers($search, $status, $perPage);
    }
}
