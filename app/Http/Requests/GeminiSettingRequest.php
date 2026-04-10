<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GeminiSettingRequest extends FormRequest
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
            'api_key' => ['nullable', 'string', 'max:2048'],
            'model_name' => ['required', 'string', 'max:255'],
            'timeout_seconds' => ['required', 'integer', 'min:5', 'max:120'],
            'temperature' => ['required', 'numeric', 'min:0', 'max:2'],
            'max_output_tokens' => ['required', 'integer', 'min:128', 'max:8192'],
            'is_enabled' => ['nullable', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'model_name.required' => 'Vui lòng nhập tên model Gemini',
            'timeout_seconds.required' => 'Vui lòng nhập timeout',
            'temperature.required' => 'Vui lòng nhập temperature',
            'max_output_tokens.required' => 'Vui lòng nhập max output tokens',
        ];
    }
}
