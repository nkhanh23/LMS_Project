<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizAttemptAnswer extends Model
{
    protected $guarded = [];

    public function attempt()
    {
        return $this->belongsTo(QuizAttempt::class, 'attempt_id', 'id');
    }

    public function question()
    {
        return $this->belongsTo(QuizQuestion::class, 'question_id', 'id');
    }

    public function selectedOption()
    {
        return $this->belongsTo(QuizOption::class, 'selected_option_id', 'id');
    }
}
