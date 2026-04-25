<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiChatMessage extends Model
{
    protected $guarded = [];

    protected $casts = [
        'meta_json' => 'array',
    ];

    public function session()
    {
        return $this->belongsTo(AiChatSession::class, 'session_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function citations()
    {
        return $this->hasMany(AiMessageCitation::class, 'message_id');
    }

    public function getAnswerStatusAttribute(): ?string
    {
        return data_get($this->meta_json, 'answer_status');
    }

    public function getEvidenceStrengthAttribute(): ?string
    {
        return data_get($this->meta_json, 'evidence_strength');
    }

    public function getSourceScopeAttribute(): ?string
    {
        return data_get($this->meta_json, 'source_scope');
    }

    public function getRetrievedChunkIdsAttribute(): array
    {
        return (array) data_get($this->meta_json, 'retrieved_chunk_ids', []);
    }

    public function isAssistant(): bool
    {
        return $this->role === 'assistant';
    }

    public function isUser(): bool
    {
        return $this->role === 'user';
    }
}
