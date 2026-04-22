<?php

namespace App\Services;

use App\Models\AiMessageCitation;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Services\Contracts\AIProviderInterface;
use Illuminate\Support\Collection;
use Throwable;

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
            courseId: (int) $course->id,
            lectureId: (int) $lecture->id
        );

        $this->chatSessionService->storeUserMessage(
            session: $session,
            userId: $userId,
            content: $question
        );

        $history = $this->chatSessionService->getRecentMessages($session, 8);

        $retrieval = $this->aiRetrieverService->retrieve(
            question: $question,
            courseId: (int) $course->id,
            lectureId: (int) $lecture->id,
            limit: 5
        );

        /** @var \Illuminate\Support\Collection $chunks */
        $chunks = $retrieval['chunks'];
        $sourceScope = $retrieval['source_scope'];
        $evidenceStrength = $retrieval['evidence_strength'];

        if ($evidenceStrength === 'none') {
            $fallbackAnswer = $this->buildNoEvidenceAnswer(
                $lecture->lecture_title ?? ('Lesson #' . $lecture->id)
            );

            $assistantMessage = $this->chatSessionService->storeAssistantMessage(
                session: $session,
                content: $fallbackAnswer,
                provider: null,
                model: null,
                meta: [
                    'answer_status' => 'no_evidence',
                    'source_scope' => $sourceScope,
                    'evidence_strength' => $evidenceStrength,
                    'prompt_version' => 'phase7_v1',
                    'retrieved_chunk_ids' => [],
                ]
            );

            return $this->formatResponse(
                sessionId: (int) $session->id,
                assistantMessage: $assistantMessage,
                answerStatus: 'no_evidence',
                evidenceStrength: $evidenceStrength,
                sourceScope: $sourceScope
            );
        }

        $prompt = $this->aiPromptBuilderService->build(
            question: $question,
            history: $history,
            chunks: $chunks,
            courseTitle: $course->course_title ?? ('Course #' . $course->id),
            lectureTitle: $lecture->lecture_title ?? ('Lesson #' . $lecture->id),
            evidenceStrength: $evidenceStrength,
            sourceScope: $sourceScope
        );

        try {
            $answerPayload = $this->aiProvider->generateAnswer($prompt, [
                'temperature' => $evidenceStrength === 'weak' ? 0.1 : 0.2,
                'max_output_tokens' => 900,
            ]);

            $answerText = trim((string) ($answerPayload['answer'] ?? ''));

            if ($evidenceStrength === 'weak') {
                $answerText = $this->prependWeakEvidenceNotice($answerText);
            }

            $answerStatus = $evidenceStrength === 'weak'
                ? 'weak_evidence'
                : 'success';

            $assistantMessage = $this->chatSessionService->storeAssistantMessage(
                session: $session,
                content: $answerText,
                provider: $answerPayload['provider'] ?? 'gemini',
                model: $answerPayload['model'] ?? null,
                meta: [
                    'answer_status' => $answerStatus,
                    'source_scope' => $sourceScope,
                    'evidence_strength' => $evidenceStrength,
                    'prompt_version' => 'phase7_v1',
                    'finish_reason' => $answerPayload['finish_reason'] ?? null,
                    'retrieved_chunk_ids' => $chunks->pluck('id')->values()->all(),
                ]
            );

            $this->storeCitations($assistantMessage->id, $chunks);

            return $this->formatResponse(
                sessionId: (int) $session->id,
                assistantMessage: $assistantMessage,
                answerStatus: $answerStatus,
                evidenceStrength: $evidenceStrength,
                sourceScope: $sourceScope
            );
        } catch (\Throwable $e) {
            report($e);

            $assistantMessage = $this->chatSessionService->storeAssistantMessage(
                session: $session,
                content: 'Xin lỗi, hệ thống AI đang bận hoặc gặp lỗi tạm thời. Bạn hãy thử lại sau ít phút.',
                provider: 'gemini',
                model: null,
                meta: [
                    'answer_status' => 'provider_error',
                    'source_scope' => $sourceScope,
                    'evidence_strength' => $evidenceStrength,
                    'prompt_version' => 'phase7_v1',
                    'error' => $e->getMessage(),
                    'retrieved_chunk_ids' => $chunks->pluck('id')->values()->all(),
                ]
            );

            $this->storeCitations($assistantMessage->id, $chunks);

            return $this->formatResponse(
                sessionId: (int) $session->id,
                assistantMessage: $assistantMessage,
                answerStatus: 'provider_error',
                evidenceStrength: $evidenceStrength,
                sourceScope: $sourceScope
            );
        }
    }

    protected function buildNoEvidenceAnswer(string $lectureTitle): string
    {
        return "Mình chưa tìm thấy bằng chứng đủ mạnh trong tài liệu của bài học hiện tại ({$lectureTitle}) để trả lời câu hỏi này. "
            . "Bạn hãy thử hỏi lại cụ thể hơn, hoặc kiểm tra xem bài học đã có tài liệu AI/KB chưa.";
    }

    private function prependWeakEvidenceNotice(string $answer): string
    {
        $notice = 'Lưu ý: mình chưa thấy đủ thông tin mạnh trong tài liệu hiện có để khẳng định hoàn toàn chắc chắn.';

        if ($answer === '') {
            return $notice;
        }

        if (str_contains(mb_strtolower($answer), 'chưa thấy đủ thông tin mạnh')) {
            return $answer;
        }

        return $notice . "\n\n" . $answer;
    }

    protected function storeCitations(int $messageId, Collection $chunks): void
    {
        foreach ($chunks->values() as $index => $chunk) {
            AiMessageCitation::query()->create([
                'message_id' => $messageId,
                'document_id' => $chunk->document_id,
                'chunk_id' => $chunk->id,
                'rank' => $index + 1,
                'score' => $chunk->relevance_score ?? null,
                'snippet' => mb_substr((string) $chunk->content, 0, 300),
            ]);
        }
    }

    protected function formatResponse(
        int $sessionId,
        $assistantMessage,
        string $answerStatus,
        string $evidenceStrength,
        string $sourceScope
    ): array {
        $citations = $assistantMessage->citations()
            ->with(['document', 'chunk'])
            ->orderBy('rank')
            ->get()
            ->map(function ($citation) {
                return [
                    'document_title' => $citation->document?->title,
                    'chunk_id' => $citation->chunk_id,
                    'snippet' => $citation->snippet,
                    'rank' => $citation->rank,
                    'score' => $citation->score,
                ];
            })
            ->values()
            ->all();

        return [
            'session_id' => $sessionId,
            'message_id' => $assistantMessage->id,
            'answer' => $assistantMessage->content,
            'answer_status' => $answerStatus,
            'evidence_strength' => $evidenceStrength,
            'source_scope' => $sourceScope,
            'citations' => $citations,
        ];
    }
}
