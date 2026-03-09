<?php

namespace App\Services;

use App\Repositories\PartnerRepository;

class PartnerService
{
    protected $partnerRepository;

    public function __construct(PartnerRepository $partnerRepository)
    {
        $this->partnerRepository = $partnerRepository;
    }

    public function getAllPartners($search = null, $perPage = 10)
    {
        return $this->partnerRepository->getAllPartners($search, $perPage);
    }
}
