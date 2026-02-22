<?php

namespace App\Service;

use App\Models\User;
use App\Repositories\PasswordUpdateRepository;

class PasswordUpdateService
{
    protected $passwordUpdateRepository;
    public function __construct(PasswordUpdateRepository $passwordUpdateRepository)
    {
        $this->passwordUpdateRepository = $passwordUpdateRepository;
    }

    public function updatePassword(array $data)
    {
        return $this->passwordUpdateRepository->updatePassword($data);
    }
}
