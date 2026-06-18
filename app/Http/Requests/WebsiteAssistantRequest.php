<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class WebsiteAssistantRequest extends FormRequest
{
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

    public function rules(): array
    {
        $rules = [];

        if ($this->routeIs('website-assistant.ask')) {
            $rules['message'] = ['required', 'string', 'min:2', 'max:2000'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'message.required' => 'Vui lòng nhập câu hỏi.',
            'message.min' => 'Câu hỏi quá ngắn.',
            'message.max' => 'Câu hỏi vượt quá độ dài cho phép.',
        ];
    }
}
