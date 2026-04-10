<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class AiDocument extends Model
{
    protected $guarded = [];

    protected $casts = [
        'indexed_at' => 'datetime',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function chunks()
    {
        return $this->hasMany(AiDocumentChunk::class, 'document_id');
    }
}
