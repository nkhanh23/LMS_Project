<?php


namespace App\Repositories;

use App\Models\Coupon;

class ApplyCouponRepository
{


    public function applyCoupon($couponName, $courseIds, $instructorIds)
    {
        try {

            // Khởi tạo mảng chứa dữ liệu giảm giá
            $discounts = [];

            foreach ($courseIds as $key => $courseId) {
                $instructorId = $instructorIds[$key];

                // Kiểm tra coupon hợp lệ cho từng khóa học và giảng viên
                $coupon = Coupon::where('coupon_code', $couponName)
                    //Mã giảm giá chỉ có hiệu lực nếu nó được tạo bởi chính giảng viên dạy khóa học đó
                    ->where('instructor_id', $instructorId)
                    // Coupon còn hoạt động
                    ->where('status', 1)
                    ->first();

                if ($coupon) {
                    $discounts[] = [
                        'course_id' => $courseId,
                        'instructor_id' => $instructorId,
                        'discount' => $coupon->coupon_discount,
                        'validity' => $coupon->coupon_validity,
                    ];
                }
            }

            return $discounts;
        } catch (\Exception $error) {
            return response()->json([
                'status' => 'error',
                'message' => 'Có lỗi xảy ra! ' . $error->getMessage(),
            ], 500);
        }
    }
}
