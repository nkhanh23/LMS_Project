<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LectureNoteRequest extends FormRequest
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
            'lecture_id' => ['required', 'integer', 'exists:course_lectures,id'],
            'note' => ['required', 'string', 'max:1000'],
            'video_second' => ['required', 'integer', 'min:0'],
        ];
    }
}
