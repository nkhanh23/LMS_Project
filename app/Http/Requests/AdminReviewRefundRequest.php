<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminReviewRefundRequest extends FormRequest
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
            'approved_amount' => 'nullable|numeric|min:0',
            'admin_note' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'approved_amount.required' => 'Vui lòng nhập số tiền hoàn trả',
            'approved_amount.numeric' => 'Số tiền hoàn trả phải là số',
            'approved_amount.min' => 'Số tiền hoàn trả phải lớn hơn 0',
            'admin_note.required' => 'Vui lòng nhập ghi chú',
            'admin_note.max' => 'Ghi chú không được vượt quá 2000 ký tự',
        ];
    }
}
