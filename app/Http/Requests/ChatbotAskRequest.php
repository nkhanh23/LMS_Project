<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ChatbotAskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    protected function prepareForValidation(): void
    {
        if ($this->has('message')) {
            $this->merge([
                'message' => trim((string) $this->input('message')),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'course_id' => ['required', 'integer', 'exists:courses,id'],
            'lecture_id' => ['required', 'integer', 'exists:course_lectures,id'],
        ];

        if ($this->routeIs('chatbot.ask')) {
            $rules['message'] = ['required', 'string', 'min:2', 'max:2000'];
        }

        return $rules;
    }
    public function messages(): array
    {
        return [
            'message.required'    => 'Vui lòng nhập câu hỏi.',
            'message.min'         => 'Câu hỏi quá ngắn.',
            'message.max'         => 'Câu hỏi vượt quá độ dài cho phép.',
            'course_id.required'  => 'Thiếu ngữ cảnh khóa học.',
            'lecture_id.required' => 'Thiếu ngữ cảnh bài học.',
        ];
    }
}
