<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureRequest extends FormRequest
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
            'course_id' => 'required|exists:courses,id', //không bắt buộc nhưng phải có trong bảng course nếu được cung cấp
            'section_id' => 'required|exists:course_sections,id', //bắt buộc phải có trong bảng course_sections
            'lecture_title' => 'required|string|max:255',
            'url' => 'nullable|url|max:255',
            'content' => 'required|string',
            'video_duration' => 'nullable',
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Vui lòng chọn khóa học',
            'course_id.exists' => 'Khóa học không tồn tại',
            'section_id.required' => 'Vui lòng chọn chương học',
            'section_id.exists' => 'Chương học không tồn tại',
            'lecture_title.required' => 'Vui lòng nhập tiêu đề bài học',
            'lecture_title.string' => 'Tiêu đề bài học phải là chuỗi',
            'lecture_title.max' => 'Tiêu đề bài học không được vượt quá 255 ký tự',
            'url.url' => 'URL không hợp lệ',
            'url.max' => 'URL không được vượt quá 255 ký tự',
            'content.required' => 'Vui lòng nhập nội dung bài học',
            'content.string' => 'Nội dung bài học phải là chuỗi',
        ];
    }
}
