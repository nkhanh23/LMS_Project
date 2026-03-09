<?php

namespace App\Repositories;

use App\Models\Wishlist;
use Illuminate\Support\Facades\Auth;

class WishlistRepository
{
    public function createWishlist($courseId)
    {
        try {
            if (!Auth::check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Bạn chưa đăng nhập!'
                ], 401);
            }
            $userId = Auth::id();
            $exists = Wishlist::where('user_id', $userId)->where('course_id', $courseId)->exists();
            if (!$exists) {
                $wishlist = Wishlist::create([
                    'user_id' => $userId,
                    'course_id' => $courseId,
                ]);
                $wishlistCount = Wishlist::where('user_id', $userId)->count();
                $wishlist_course = Wishlist::where('user_id', $userId)->get();
                return response()->json([
                    'status' => 'success',
                    'message' => 'Khóa học đã được thêm vào danh sách yêu thích!',
                    'wishlist_count' => $wishlistCount,
                    'wishlist_course' => $wishlist_course
                ], 200);
            } else {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Khóa học đã tồn tại trong danh sách yêu thích!',
                ], 409);
            }
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Đã xảy ra lỗi khi thêm khóa học vào danh sách yêu thích!',
            ], 500);
        }
    }
}
