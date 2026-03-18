<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizOption extends Model
{
    protected $guarded = [];

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id', 'id');
    }
}
