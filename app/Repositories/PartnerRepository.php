<?php

namespace App\Repositories;

use App\Models\Partner;

class PartnerRepository
{
    public function getAllPartners($search = null, $perPage = 10)
    {
        return Partner::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%");
        })->latest()->paginate($perPage);
    }
}
