<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiMessageCitation extends Model
{
    protected $guarded = [];
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
}
