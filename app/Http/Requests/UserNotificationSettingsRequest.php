<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserNotificationSettingsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    public function rules(): array
    {
        return [
            'notify_new_courses' => ['nullable', 'boolean'],
            'notify_learning_reminders' => ['nullable', 'boolean'],
            'notify_quiz_discussion_messages' => ['nullable', 'boolean'],
        ];
    }
}
