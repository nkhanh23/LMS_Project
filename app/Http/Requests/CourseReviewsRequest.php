<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseReviewsRequest extends FormRequest
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
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'rating'    => ['required', 'integer', 'between:1,5'],
            'comment'   => ['required', 'string', 'min:5', 'max:1000'],
        ];
    }

    public function messages()
    {
        return [
            'course_id.required' => 'Khóa học ID là bắt buộc.',
            'course_id.exists'   => 'Khóa học không tồn tại.',
            'rating.required'    => 'Xếp hạng là bắt buộc.',
            'rating.between'     => 'Xếp hạng phải trong khoảng 1 đến 5.',
            'comment.required'   => 'Bình luận là bắt buộc.',
            'comment.min'        => 'Bình luận phải có ít nhất 5 ký tự.',
            'comment.max'        => 'Bình luận không được vượt quá 1000 ký tự.',
        ];
    }
}
