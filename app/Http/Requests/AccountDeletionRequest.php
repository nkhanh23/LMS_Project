<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AccountDeletionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'password' => ['required', 'current_password'],
            'confirm_delete_request' => ['accepted'],
            'account_deletion_reason' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'password.current_password' => 'Mật khẩu hiện tại không chính xác.',
            'confirm_delete_request.accepted' => 'Bạn cần xác nhận yêu cầu xóa tài khoản.',
        ];
    }
}
