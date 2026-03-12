<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\ApplyCouponRepository;
use App\Repositories\CategoryRepository;

class ApplyCouponService
{
    protected $applyCouponRepository;
    public function __construct(ApplyCouponRepository $applyCouponRepository)
    {
        $this->applyCouponRepository = $applyCouponRepository;
    }

    public function applyCoupon($couponName, $courseIds, $instructorIds)
    {
        return $this->applyCouponRepository->applyCoupon($couponName, $courseIds, $instructorIds);
    }
}
