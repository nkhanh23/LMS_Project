<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorRequest extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
