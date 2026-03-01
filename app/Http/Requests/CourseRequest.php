<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
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
        $courseId = $this->route('course');
        return [
            'category_id' => 'required|integer|exists:categories,id',
            'subcategory_id' => 'required|integer|exists:sub_categories,id',
            'instructor_id' => 'required|integer|exists:users,id', // Assuming instructors are stored in users table
            'course_image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048', // Max 2MB
            'course_title' => 'required|string',
            'course_name' => 'required|string',
            'course_name_slug' => $courseId
                ? "nullable|string|unique:courses,course_name_slug,{$courseId}"
                : "nullable|string|unique:courses,course_name_slug",
            'description' => 'required|string',
            'video_url' => 'required|url',
            'label' => 'nullable|string|max:100',
            'duration' => 'nullable',

            'resources' => 'nullable|string|max:255',
            'certificate' => 'nullable|string|max:100',
            'selling_price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0|lte:selling_price', // Discount should not exceed selling price
            'prerequisites' => 'nullable|string|max:10000',
            'bestseller' => 'nullable|in:yes,no',
            'featured' => 'nullable|in:yes,no',
            'highestrated' => 'nullable|in:yes,no',
            'course_goals'   => 'nullable|array',
            'course_goals.*' => 'nullable'
        ];
    }

    public function messages(): array
    {
        return [
            'category_id.required' => 'Vui lòng chọn danh mục chính.',
            'category_id.exists'   => 'Danh mục đã chọn không tồn tại.',

            'subcategory_id.required' => 'Vui lòng chọn danh mục con.',
            'subcategory_id.exists'   => 'Danh mục con không hợp lệ.',

            'instructor_id.required' => 'Vui lòng chọn giảng viên.',
            'instructor_id.exists'   => 'Giảng viên không tồn tại trong hệ thống.',

            'course_image.image' => 'File tải lên phải là hình ảnh.',
            'course_image.mimes' => 'Hình ảnh phải có định dạng: jpg, jpeg, png, gif.',
            'course_image.max'   => 'Dung lượng ảnh không được vượt quá 2MB.',

            'course_title.required' => 'Tiêu đề khóa học không được để trống.',
            'course_name.required'  => 'Tên khóa học không được để trống.',

            'course_name_slug.unique' => 'Đường dẫn (slug) này đã tồn tại, vui lòng chọn tên khác.',

            'description.required' => 'Mô tả khóa học là bắt buộc.',
            'video_url.required'   => 'Link video giới thiệu không được để trống.',
            'video_url.url'        => 'Định dạng link video không hợp lệ.',

            'selling_price.required' => 'Vui lòng nhập giá bán.',
            'selling_price.integer'  => 'Giá bán phải là con số.',
            'selling_price.min'      => 'Giá bán không được nhỏ hơn 0.',

            'discount_price.integer' => 'Giá giảm phải là con số.',
            'discount_price.lte'     => 'Giá giảm phải nhỏ hơn hoặc bằng giá gốc.',

            'bestseller.in'     => 'Giá trị Bestseller không hợp lệ.',
            'featured.in'       => 'Giá trị Nổi bật không hợp lệ.',
            'highestrated.in'   => 'Giá trị Đánh giá cao không hợp lệ.',
        ];
    }
}
