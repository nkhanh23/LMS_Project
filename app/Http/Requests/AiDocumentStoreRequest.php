<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AiDocumentStoreRequest extends FormRequest
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
        $this->merge([
            'title' => trim((string) $this->input('title')),
            'content' => $this->has('content')
                ? trim((string) $this->input('content'))
                : null,
            'source_type' => $this->filled('source_type')
                ? trim((string) $this->input('source_type'))
                : 'manual_upload',
            'language' => $this->filled('language')
                ? trim((string) $this->input('language'))
                : 'vi',
        ]);
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
            'lecture_id' => ['nullable', 'integer', 'exists:course_lectures,id'],
            'title' => ['required', 'string', 'max:255'],
            'content' => ['nullable', 'string', 'min:30'],
            'source_type' => ['nullable', 'string', 'in:manual_upload,transcript,pdf,docx,txt,md'],
            'language' => ['nullable', 'string', 'max:10'],
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                if (!$this->filled('content') && !$this->hasFile('file')) {
                    $validator->errors()->add('file', 'Bạn phải nhập content hoặc upload file.');
                }
            },
        ];
    }

    public function messages(): array
    {
        return [
            'course_id.required' => 'Thiếu course_id.',
            'lecture_id.exists' => 'lecture_id không hợp lệ.',
            'title.required' => 'Vui lòng nhập tiêu đề tài liệu.',
            'content.min' => 'Nội dung tài liệu quá ngắn để index.',
            'source_type.in' => 'Loại tài liệu không hợp lệ.',
        ];
    }
}
