<?php

namespace App\Repositories;

use App\Models\Cart;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cookie;


class CartRepository
{
    public function createCart($course_id, $request)
    {
        try {
            // lấy guest_token từ cookie
            $guestToken = $request->cookie('guest_token') ?? Str::uuid();
            //set guest_token nếu chưa set
            if (!$request->cookie('guest_token')) {
                Cookie::queue('guest_token', $guestToken, 60 * 24 * 30); //set cookie guest_token 30 ngày
            }
            //check nếu course đã tồn tại trong cart cho guest_token
            $existingCart = Cart::where('guest_token', $guestToken)
                ->where('course_id', $course_id)
                ->first();
            if ($existingCart) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Khóa học đã tồn tại trong giỏ hàng !'
                ], 400);
            }
            //tạo cart cho guest_token
            Cart::create([
                'guest_token' => $guestToken,
                'course_id' => $course_id,
            ]);
            return response()->json([
                'status' => 'success',
                'message' => 'Thêm khóa học vào giỏ hàng thành công !'
            ], 200);
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra!' . $error->getMessage()
            ], 500);
        }
    }

    public function viewCart($request)
    {
        try {
            // lấy guest_token từ cookie
            $guestToken = $request->cookie('guest_token');
            // lấy tất cả cart của guest_token
            $cart = Cart::with('course')
                ->where('guest_token', $guestToken)
                ->latest()
                ->get();

            return $cart;
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra!' . $error->getMessage()
            ], 500);
        }
    }
}
