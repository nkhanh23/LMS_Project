<?php

namespace App\Services;

use App\Models\AiChatMessage;
use App\Models\AiChatSession;
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

        return AiChatSession::query()->create([
            'user_id' => $userId,
            'course_id' => $courseId,
            'lecture_id' => $lectureId,
            'title' => 'Chat bài học #' . $lectureId,
            'status' => 'active',
            'last_activity_at' => now(),
        ]);
    }

    public function storeUserMessage(AiChatSession $session, int $userId, string $content): AiChatMessage
    {
        $message = AiChatMessage::query()->create([
            'session_id' => $session->id,
            'user_id' => $userId,
            'role' => 'user',
            'content' => $content,
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
            'meta_json' => $meta,
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

    protected function touchSession(AiChatSession $session): void
    {
        $session->update([
            'last_activity_at' => Carbon::now(),
        ]);
    }
}
