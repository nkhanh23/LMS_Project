<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserSetting extends Model
{
    protected $guarded = [];

    protected $casts = [
        'notify_new_courses' => 'boolean',
        'notify_learning_reminders' => 'boolean',
        'notify_quiz_discussion_messages' => 'boolean',
        'account_deletion_requested_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
