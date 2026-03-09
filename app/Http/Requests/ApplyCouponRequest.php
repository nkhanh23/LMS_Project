<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ApplyCouponRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'coupon' => [
                'required',
                'string',
                'exists:coupons,coupon_code',
                function ($attribute, $value, $fail) {
                    $coupon = \App\Models\Coupon::where('coupon_code', $value)->first();
                    if ($coupon && \Carbon\Carbon::now()->greaterThan(\Carbon\Carbon::parse($coupon->coupon_validity))) {
                        $fail('Mã giảm giá đã hết hạn.');
                    }
                },
            ],
            'course_id' => 'required|array',
            'course_id.*' => 'exists:courses,id', // Validate each course_id
            'instructor_id' => 'required|array',
            'instructor_id.*' => 'exists:users,id', // Validate each instructor_id
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'coupon.required' => 'Mã giảm giá là bắt buộc.',
            'coupon.exists' => 'Mã giảm giá không hợp lệ hoặc không tồn tại.',
            'course_id.required' => 'Khóa học là bắt buộc.',
            'course_id.*.exists' => 'Khóa học đã chọn không hợp lệ.',
            'instructor_id.required' => 'Giảng viên là bắt buộc.',
            'instructor_id.*.exists' => 'Giảng viên đã chọn không hợp lệ.',
        ];
    }
}
