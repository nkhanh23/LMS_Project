<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TranscriptJob extends Model
{
    protected $guarded = [];

    protected $casts = [
        'request_payload' => 'array',
        'response_payload' => 'array',
        'started_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    public function lecture()
    {
        return $this->belongsTo(CourseLecture::class, 'lecture_id');
    }

    public function course()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function document()
    {
        return $this->belongsTo(AiDocument::class, 'document_id');
    }

    public function markQueued(array $payload = []): void
    {
        $this->update([
            'status' => 'queued',
            'progress' => 0,
            'error_message' => null,
            'request_payload' => $payload ?: $this->request_payload,
            'started_at' => null,
            'finished_at' => null,
        ]);
    }

    public function markProcessing(int $progress = 10): void
    {
        $this->update([
            'status' => 'processing',
            'progress' => $progress,
            'started_at' => $this->started_at ?: now(),
            'error_message' => null,
        ]);
    }

    public function markFailed(string $message, ?array $responsePayload = null): void
    {
        $this->update([
            'status' => 'failed',
            'error_message' => $message,
            'response_payload' => $responsePayload,
            'finished_at' => now(),
        ]);
    }

    public function markDone(?int $documentId = null, ?array $responsePayload = null): void
    {
        $this->update([
            'status' => 'done',
            'progress' => 100,
            'document_id' => $documentId ?? $this->document_id,
            'response_payload' => $responsePayload,
            'finished_at' => now(),
        ]);
    }
}
