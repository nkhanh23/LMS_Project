<?php

namespace App\Services;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
use App\Models\CourseLecture;
use Carbon\Carbon;

class ChatSessionService
{
    public function getOrCreateSession(int $userId, int $courseId, int $lectureId): AiChatSession
    {
        $session = AiChatSession::query()
            ->where('user_id', $userId)
            ->where('course_id', $courseId)
            ->where('lecture_id', $lectureId)
            ->where('status', 'active')
            ->latest('id')
            ->first();

        if ($session) {
            return $session;
        }

        $lecture = CourseLecture::find($lectureId);
        $title = $lecture ? 'Chat bài học: ' . ($lecture->lecture_title ?? $lecture->title) : 'Chat bài học #' . $lectureId;

        return AiChatSession::query()->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'lecture_id' => $lectureId,
            'title' => $title,
            'status' => 'active',
            'last_activity_at' => now(),
        ]);
    }

    public function storeUserMessage(
        AiChatSession $session,
        int $userId,
        string $content,
        ?array $meta = null
    ): AiChatMessage {
        $message = AiChatMessage::query()->create([
            'session_id' => $session->id,
            'user_id' => $userId,
            'role' => 'user',
            'content' => $content,
            'meta_json' => array_merge([
                'answer_status' => null,
                'evidence_strength' => null,
                'source_scope' => 'lesson',
                'retrieved_chunk_ids' => [],
            ], $meta ?? []),
        ]);

        $this->touchSession($session);

        return $message;
    }

    public function storeAssistantMessage(
        AiChatSession $session,
        string $content,
        ?string $provider = null,
        ?string $model = null,
        ?array $meta = null
    ): AiChatMessage {
        $message = AiChatMessage::query()->create([
            'session_id' => $session->id,
            'user_id' => null,
            'role' => 'assistant',
            'content' => $content,
            'provider' => $provider,
            'model' => $model,
            'meta_json' => array_merge([
                'answer_status' => 'success',
                'evidence_strength' => null,
                'source_scope' => null,
                'retrieved_chunk_ids' => [],
                'prompt_version' => null,
                'finish_reason' => null,
                'latency_ms' => null,
            ], $meta ?? []),
        ]);

        $this->touchSession($session);

        return $message;
    }

    public function getMessagesForSession(AiChatSession $session, int $limit = 50)
    {
        return AiChatMessage::query()
            ->where('session_id', $session->id)
            ->latest('id')
            ->take($limit)
            ->get()
            ->sortBy('id')
            ->values();
    }

    public function getSessionMessagesWithCitations(AiChatSession $session, int $limit = 50)
    {
        return AiChatMessage::query()
            ->where('session_id', $session->id)
            ->with([
                'citations.document',
                'citations.chunk',
            ])
            ->latest('id')
            ->take($limit)
            ->get()
            ->sortBy('id')
            ->values();
    }

    public function getStructuredHistory(AiChatSession $session, int $limit = 50): array
    {
        return $this->getSessionMessagesWithCitations($session, $limit)
            ->map(function (AiChatMessage $message) {
                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'provider' => $message->provider,
                    'model' => $message->model,
                    'created_at' => optional($message->created_at)->toDateTimeString(),
                    'answer_status' => $message->answer_status,
                    'evidence_strength' => $message->evidence_strength,
                    'source_scope' => $message->source_scope,
                    'retrieved_chunk_ids' => $message->retrieved_chunk_ids,
                    'citations' => $message->citations
                        ->sortBy('rank')
                        ->values()
                        ->map(fn($citation) => $citation->toHistoryArray())
                        ->all(),
                ];
            })
            ->values()
            ->all();
    }

    public function getRecentMessages(AiChatSession $session, int $limit = 8)
    {
        return $session->messages()
            ->latest('id')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    protected function touchSession(AiChatSession $session): void
    {
        $session->update([
            'last_activity_at' => Carbon::now(),
        ]);
    }
}
