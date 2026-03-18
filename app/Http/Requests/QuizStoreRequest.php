<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizStoreRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'passing_score' => ['nullable', 'integer', 'min:0'],

            'questions' => ['required', 'array', 'min:1'],
            'questions.*.question_text' => ['required', 'string'],
            'questions.*.points' => ['nullable', 'integer', 'min:1'],
            'questions.*.options' => ['required', 'array'],
            'questions.*.options.*' => ['required', 'string'],
            'questions.*.correct_option' => ['required', 'integer', 'min:0', 'max:3'],
            'questions.*.explanation' => ['nullable', 'string'],

            'shuffle_questions' => ['nullable', 'boolean'],
            'show_result_immediately' => ['nullable', 'boolean'],
            'max_attempts' => ['nullable', 'integer', 'min:1'],
            'time_limit' => ['nullable', 'integer', 'min:1'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'Tiêu đề quiz là bắt buộc.',
            'questions.required' => 'Quiz phải có ít nhất 1 câu hỏi.',
            'questions.min' => 'Quiz phải có ít nhất 1 câu hỏi.',
            'questions.*.question_text.required' => 'Nội dung câu hỏi là bắt buộc.',
            'questions.*.options.required' => 'Câu hỏi phải có 4 đáp án.',
            'questions.*.options.*.required' => 'Nội dung đáp án không được để trống.',
            'questions.*.correct_option.required' => 'Bạn phải chọn đáp án đúng.',
        ];
    }
}
