<?php

namespace App\Repositories;

use App\Models\User;

class AdminUserRepository
{
    public function getUsers($search = null, $status = null, $perPage = 10)
    {
        return User::where('role', 'user')
            ->when($search, function ($query, $search) {
                return $query->where(function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%{$search}%")
                        ->orWhere('email', 'LIKE', "%{$search}%")
                        ->orWhere('phone', 'LIKE', "%{$search}%");
                });
            })
            ->when($status !== null, function ($query) use ($status) {
                return $query->where('status', $status);
            })
            ->latest()
            ->paginate($perPage);
    }
}
