<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MailRequest extends FormRequest
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
            'mailer' => 'required|string',
            'host' => 'required|string',
            'port' => 'required|integer',
            'username' => 'required|string',
            'password' => 'required|string',
            'encryption' => 'required|string',
            'from_address' => 'required|email',
        ];
    }

    public function messages(): array
    {
        return [
            'mailer.required' => 'Mail Mailer không được để trống',
            'host.required' => 'Mail Host không được để trống',
            'port.required' => 'Mail Port không được để trống',
            'username.required' => 'Mail Username không được để trống',
            'password.required' => 'Mail Password không được để trống',
            'encryption.required' => 'Mail Encryption không được để trống',
            'from_address.required' => 'Mail From Address không được để trống',
            'from_address.email' => 'Mail From Address phải là email',
        ];
    }
}
