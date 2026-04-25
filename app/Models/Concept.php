<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Concept extends Model
{
    protected $guarded = [];

    protected $casts = [
        'synonyms_json' => 'array',
        'is_active' => 'boolean',
    ];

    public function lectures()
    {
        return $this->belongsToMany(
            CourseLecture::class,
            'lesson_concepts',
            'concept_id',
            'lecture_id'
        )->withTimestamps();
    }

    public function documents()
    {
        return $this->belongsToMany(
            AiDocument::class,
            'document_concepts',
            'concept_id',
            'document_id'
        )->withTimestamps();
    }
}
