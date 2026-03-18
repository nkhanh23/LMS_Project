<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $guarded = [];

    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id', 'id');
    }

    public function options()
    {
        return $this->hasMany(QuizOption::class, 'question_id', 'id')->orderBy('sort_order');
    }

    public function attemptAnswers()
    {
        return $this->hasMany(QuizAttemptAnswer::class, 'question_id', 'id');
    }
}
