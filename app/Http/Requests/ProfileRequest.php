<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'first_name' => 'nullable|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->user()->id,
            'phone' => 'nullable|string|max:15',
            'address' => 'nullable|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'bio' => 'nullable|string|max:65535',
            'city' => 'nullable',
            'country' => 'nullable',
            'gender' => 'nullable',
            'experience' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'first_name.required' => 'Tên không được để trống',
            'last_name.required' => 'Họ không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'email.unique' => 'Email đã tồn tại',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.max' => 'Số điện thoại không được vượt quá 15 ký tự',
            'address.required' => 'Địa chỉ không được để trống',
            'address.max' => 'Địa chỉ không được vượt quá 255 ký tự',
            'photo.required' => 'Ảnh không được để trống',
            'photo.image' => 'Ảnh phải là định dạng ảnh',
            'photo.mimes' => 'Ảnh phải là định dạng jpeg, png, jpg, gif',
            'photo.max' => 'Ảnh phải nhỏ hơn 10MB',
            'bio.required' => 'Giới thiệu không được để trống',
            'bio.max' => 'Giới thiệu không được vượt quá 65535 ký tự',
            'city.required' => 'Thành phố không được để trống',
            'country.required' => 'Quốc gia không được để trống',
            'gender.required' => 'Giới tính không được để trống',
            'experience.required' => 'Kinh nghiệm không được để trống',
        ];
    }
}
