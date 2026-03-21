<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserRefundRequestStoreRequest extends FormRequest
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
            'type' => 'required|in:refund,cancel',
            'reason' => 'required|string|max:2000',
            'requested_amount' => 'nullable|numeric|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Vui lòng chọn loại yêu cầu',
            'type.in' => 'Loại yêu cầu không hợp lệ',
            'reason.required' => 'Vui lòng nhập lý do',
            'reason.max' => 'Lý do không được vượt quá 2000 ký tự',
            'requested_amount.required' => 'Vui lòng nhập số tiền yêu cầu',
            'requested_amount.numeric' => 'Số tiền yêu cầu phải là số',
            'requested_amount.min' => 'Số tiền yêu cầu phải lớn hơn 0',
        ];
    }
}
