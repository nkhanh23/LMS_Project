<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class AdminManualRefundRequest extends FormRequest
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
            'approved_amount' => 'required|numeric|min:0',
            'reason' => 'required|string|max:2000',
            'admin_note' => 'nullable|string|max:2000',
        ];
    }

    public function messages(): array
    {
        return [
            'approved_amount.required' => 'Vui lòng nhập số tiền hoàn trả',
            'approved_amount.numeric' => 'Số tiền hoàn trả phải là số',
            'approved_amount.min' => 'Số tiền hoàn trả không được nhỏ hơn 0',
            'reason.required' => 'Vui lòng nhập lý do',
            'reason.max' => 'Lý do không được vượt quá 2000 ký tự',
            'admin_note.max' => 'Ghi chú không được vượt quá 2000 ký tự',
        ];
    }
}
