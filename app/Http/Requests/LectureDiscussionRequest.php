<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureDiscussionRequest extends FormRequest
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
            'course_id' => ['required', 'exists:courses,id'],
            'lecture_id' => ['required', 'exists:course_lectures,id'],
            'content' => ['required', 'string', 'max:2000'],
            'parent_id' => ['nullable', 'exists:lecture_discussions,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Vui lòng chọn khóa học',
            'course_id.exists' => 'Khóa học không tồn tại',
            'lecture_id.required' => 'Vui lòng chọn bài giảng',
            'lecture_id.exists' => 'Bài giảng không tồn tại',
            'content.required' => 'Vui lòng nhập nội dung',
            'content.max' => 'Nội dung tối đa 2000 ký tự',
            'parent_id.exists' => 'Phản hồi không tồn tại',
        ];
    }
}
