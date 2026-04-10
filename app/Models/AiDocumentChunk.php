<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiDocumentChunk extends Model
{
    protected $guarded = [];
    protected $casts = [
        'meta_json' => 'array',
    ];

    public function document()
    {
        return $this->belongsTo(AiDocument::class, 'document_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }
}
