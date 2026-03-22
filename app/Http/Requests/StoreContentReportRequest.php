<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class StoreContentReportRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'reason_code' => [
                'required',
                'string',
                Rule::in([
                    'spam',
                    'abuse',
                    'harassment',
                    'hate_speech',
                    'adult',
                    'misinformation',
                    'other',
                ]),
            ],
            'description' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'reason_code.required' => 'Vui lòng chọn lý do báo cáo.',
            'reason_code.in' => 'Lý do báo cáo không hợp lệ.',
            'description.max' => 'Mô tả không được vượt quá 2000 ký tự.',
        ];
    }
}
