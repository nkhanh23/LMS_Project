<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    protected $guarded = [];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }
    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
