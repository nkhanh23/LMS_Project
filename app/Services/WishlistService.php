<?php

namespace App\Services;

use App\Repositories\WishlistRepository;

class WishlistService
{
    protected $wishlistRepository;

    public function __construct(WishlistRepository $wishlistRepository)
    {
        $this->wishlistRepository = $wishlistRepository;
    }

    public function createWishlist($courseId)
    {
        return $this->wishlistRepository->createWishlist($courseId);
    }
}
