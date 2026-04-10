<?php

namespace App\Services;

use App\Models\AiMessageCitation;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Services\Contracts\AIProviderInterface;

class AiChatOrchestratorService
{
    public function __construct(
        protected ChatSessionService $chatSessionService,
        protected AiRetrieverService $aiRetrieverService,
        protected AiPromptBuilderService $aiPromptBuilderService,
        protected AIProviderInterface $aiProvider
    ) {}

    public function handle(
        int $userId,
        Course $course,
        CourseLecture $lecture,
        string $question
    ): array {
        $session = $this->chatSessionService->getOrCreateSession(
            userId: $userId,
            courseId: $course->id,
            lectureId: $lecture->id
        );

        $this->chatSessionService->storeUserMessage(
            session: $session,
            userId: $userId,
            content: $question
        );

        $history = $this->chatSessionService->getRecentMessages($session, 8);

        $chunks = $this->aiRetrieverService->retrieve(
            question: $question,
            courseId: $course->id,
            lectureId: $lecture->id,
            limit: 5
        );

        $prompt = $this->aiPromptBuilderService->build(
            question: $question,
            history: $history,
            chunks: $chunks,
            courseTitle: $course->course_title ?? ('Course #' . $course->id),
            lectureTitle: $lecture->lecture_title ?? ('Lesson #' . $lecture->id),
        );

        $answerPayload = $this->aiProvider->generateAnswer($prompt);

        $assistantMessage = $this->chatSessionService->storeAssistantMessage(
            session: $session,
            content: $answerPayload['answer'],
            provider: $answerPayload['provider'] ?? 'gemini',
            model: $answerPayload['model'] ?? null,
            meta: [
                'finish_reason' => $answerPayload['finish_reason'] ?? null,
                'retrieved_chunk_ids' => $chunks->pluck('id')->values()->all(),
            ]
        );

        foreach ($chunks as $index => $chunk) {
            AiMessageCitation::query()->create([
                'message_id' => $assistantMessage->id,
                'document_id' => $chunk->document_id,
                'chunk_id' => $chunk->id,
                'rank' => $index + 1,
                'score' => $chunk->relevance_score ?? null,
                'snippet' => mb_substr($chunk->content, 0, 300),
            ]);
        }

        return [
            'session_id' => $session->id,
            'answer' => $assistantMessage->content,
            'citations' => $assistantMessage->citations()
                ->with(['document', 'chunk'])
                ->orderBy('rank')
                ->get()
                ->map(function ($citation) {
                    return [
                        'document_title' => $citation->document?->title,
                        'chunk_id' => $citation->chunk_id,
                        'snippet' => $citation->snippet,
                        'rank' => $citation->rank,
                    ];
                })
                ->values()
                ->all(),
        ];
    }

    public function getRecentMessages(\App\Models\AiChatSession $session, int $limit = 8)
    {
        return $session->messages()
            ->latest('id')
            ->limit($limit)
            ->get()
            ->reverse()
            ->values();
    }

    public function getSessionMessagesWithCitations(\App\Models\AiChatSession $session)
    {
        return $session->messages()
            ->with('citations.document')
            ->orderBy('id')
            ->get();
    }

    protected function touchSession(\App\Models\AiChatSession $session): void
    {
        $session->update([
            'last_activity_at' => now(),
        ]);
    }
}
