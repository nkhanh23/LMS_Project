<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstructorRiskScore extends Model
{
    protected $guarded = [];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }
}
