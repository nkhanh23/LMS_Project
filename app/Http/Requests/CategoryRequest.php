<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
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
        // Lấy ID từ route. Nếu là trang create thì nó sẽ là null.
        $categoryId = $this->route('category');

        return [
            'name' => 'required|string|max:255',
            // Dùng cú pháp mảng + Rule Class để tương thích mọi Database
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('categories', 'slug')->ignore($categoryId),
            ],
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:15360',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Tên danh mục không được để trống',
            'slug.required' => 'Slug không được để trống',
            'image.required' => 'Hình ảnh không được để trống',
            'image.image' => 'Hình ảnh phải là file ảnh',
            'image.mimes' => 'Hình ảnh phải có định dạng jpeg,png,jpg,gif',
            'image.max' => 'Hình ảnh không được vượt quá 15MB',
        ];
    }
}
