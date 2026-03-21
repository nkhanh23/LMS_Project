<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminManualCancelRequest extends FormRequest
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
            'reason' => 'required|string|max:2000',
            'admin_note' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'reason.required' => 'Vui lòng nhập lý do',
            'reason.max' => 'Lý do không được vượt quá 2000 ký tự',
            'admin_note.required' => 'Vui lòng nhập ghi chú',
            'admin_note.max' => 'Ghi chú không được vượt quá 2000 ký tự',
        ];
    }
}
