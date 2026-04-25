<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiMessageCitation extends Model
{
    protected $guarded = [];
    protected $casts = [
        'score' => 'float',
    ];
    public function message()
    {
        return $this->belongsTo(AiChatMessage::class, 'message_id');
    }

    public function document()
    {
        return $this->belongsTo(AiDocument::class, 'document_id');
    }

    public function chunk()
    {
        return $this->belongsTo(AiDocumentChunk::class, 'chunk_id');
    }

    public function toHistoryArray(): array
    {
        return [
            'document_title' => $this->document?->title,
            'document_id' => $this->document_id,
            'chunk_id' => $this->chunk_id,
            'rank' => $this->rank,
            'score' => $this->score,
            'snippet' => $this->snippet,
        ];
    }
}
