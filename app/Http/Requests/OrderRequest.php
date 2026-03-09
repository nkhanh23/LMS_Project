<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'phone' => 'required|string',
            'address' => 'required|string',
            'payment_type' => 'required|string',
            'course_id' => 'required|array',
            'instructor_id' => 'required|array',
            'course_name' => 'required|array',
            'course_image' => 'required|array',
            'course_price' => 'required|array',
            'total_price' => 'required|numeric',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Vui lòng nhập tên.',
            'first_name.string' => 'Tên phải là một chuỗi ký tự.',
            'last_name.required' => 'Vui lòng nhập họ.',
            'last_name.string' => 'Họ phải là một chuỗi ký tự.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Email không đúng định dạng.',
            'phone.required' => 'Vui lòng nhập số điện thoại.',
            'phone.string' => 'Số điện thoại phải là một chuỗi ký tự.',
            'address.required' => 'Vui lòng nhập địa chỉ.',
            'address.string' => 'Địa chỉ phải là một chuỗi ký tự.',
            'payment_type.required' => 'Vui lòng chọn phương thức thanh toán.',
            'payment_type.string' => 'Phương thức thanh toán không hợp lệ.',
            'course_id.required' => 'Thiếu thông tin khóa học.',
            'course_id.array' => 'Định dạng khóa học không hợp lệ.',
            'instructor_id.required' => 'Thiếu thông tin giảng viên.',
            'instructor_id.array' => 'Định dạng giảng viên không hợp lệ.',
            'course_name.required' => 'Thiếu tên khóa học.',
            'course_name.array' => 'Định dạng tên khóa học không hợp lệ.',
            'course_image.required' => 'Thiếu hình ảnh khóa học.',
            'course_image.array' => 'Định dạng hình ảnh khóa học không hợp lệ.',
            'course_price.required' => 'Thiếu giá khóa học.',
            'course_price.array' => 'Định dạng giá khóa học không hợp lệ.',
            'total_price.required' => 'Thiếu tổng thành tiền.',
            'total_price.numeric' => 'Tổng thành tiền phải là một con số.',
        ];
    }
}
