<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CouponRequest extends FormRequest
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
        $couponId = $this->route('coupon');
        return [
            'coupon_code' => 'required|string|max:255|unique:coupons,coupon_code' . ($couponId ? ',' . $couponId : ''),
            'coupon_discount' => 'required|numeric|min:0|max:10000',
            'discount_validity' => 'required|date|after_or_equal:today',
            'status' => 'nullable|integer|in:0,1',
        ];
    }

    public function messages()
    {
        return [
            'coupon_code.required' => 'Tên mã giảm giá không được để trống',
            'coupon_code.unique' => 'Mã giảm giá này đã tồn tại',
            'coupon_discount.required' => 'Mức giảm giá không được để trống',
            'discount_validity.required' => 'Ngày hết hạn không được để trống',
            'status.required' => 'Trạng thái không được để trống',
        ];
    }
}
