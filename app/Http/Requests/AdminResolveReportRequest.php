<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class AdminResolveReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check() && Auth::user()->role === 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'action' => [
                'required',
                'string',
                Rule::in([
                    'dismiss',
                    'hide_content',
                    'delete_content',
                    'lock_course',
                    'lock_instructor',
                ]),
            ],
            'resolution_note' => 'required|string|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'action.required' => 'Vui lòng chọn hành động xử lý.',
            'action.in' => 'Hành động xử lý không hợp lệ.',
            'resolution_note.required' => 'Vui lòng nhập ghi chú xử lý.',
            'resolution_note.max' => 'Ghi chú không được vượt quá 5000 ký tự.',
        ];
    }
}
