<?php

namespace App\Services;

use App\Models\AiChatSession;

class WebsiteAssistantService
{
    public function __construct(
        protected ChatSessionService $chatSessionService,
        protected WebsiteAssistantParserService $websiteAssistantParserService,
        protected CourseEntityResolverService $courseEntityResolverService,
        protected QuizEntityResolverService $quizEntityResolverService,
        protected WebsiteAssistantAnswerService $websiteAssistantAnswerService,
        protected WebsiteKnowledgeService $websiteKnowledgeService,
        protected LearnerInsightService $learnerInsightService,
        protected LearnerRefundService $learnerRefundService
    ) {}

    public function getOrCreateSession(int $userId): AiChatSession
    {
        return $this->chatSessionService->getOrCreateWebsiteSession($userId);
    }

    public function getHistory(int $userId, int $limit = 50): array
    {
        $session = $this->getOrCreateSession($userId);

        return [
            'session_id' => $session->id,
            'mode' => $session->mode,
            'scope' => $session->scope,
            'session_status' => $session->status,
            'title' => $session->title,
            'last_activity_at' => optional($session->last_activity_at)->toDateTimeString(),
            'messages' => $this->chatSessionService->getStructuredHistory($session, $limit),
        ];
    }

    public function createNewSession(int $userId): array
    {
        $session = $this->chatSessionService->createNewSessionForMode('website', $userId);

        return [
            'session_id' => $session->id,
            'mode' => $session->mode,
            'scope' => $session->scope,
            'session_status' => $session->status,
            'title' => $session->title,
            'last_activity_at' => optional($session->last_activity_at)->toDateTimeString(),
            'messages' => [],
        ];
    }

    public function ask(int $userId, string $message): array
    {
        $session = $this->getOrCreateSession($userId);
        $parserResult = $this->parseQuestion($session, $message);
        $resolvedEntities = $this->buildResolvedEntities($parserResult, $message);

        [$quizResolution, $courseResolution] = $this->resolveEntities(
            $userId,
            $parserResult['primary_intent'],
            $resolvedEntities
        );

        $this->chatSessionService->storeUserMessage(
            session: $session,
            userId: $userId,
            content: $message,
            meta: [
                'intent' => $parserResult['primary_intent'],
                'resolved_entities' => $resolvedEntities,
                'source_type' => 'website_parser',
                'data_status' => 'parsed',
                'source_scope' => 'website',
            ]
        );

        $intentPayload = null;
        $assistantProvider = null;
        $assistantModel = null;
        $assistantSourceType = 'website_parser';
        $assistantDataStatus = 'parsed';
        $assistantContent = 'Da phan tich cau hoi cua ban.';

        $resolutionMessage = $this->getResolutionMessage($parserResult, $quizResolution, $courseResolution);

        if ($resolutionMessage !== null) {
            [$assistantContent, $assistantDataStatus] = $resolutionMessage;
        } else {
            $intentPayload = $this->getIntentPayload($parserResult['primary_intent'], $userId, $resolvedEntities);
            $answerPayload = $this->websiteAssistantAnswerService->generate($message, $intentPayload);
            $assistantContent = $answerPayload['content'];
            $assistantDataStatus = $answerPayload['answer_status'] ?? ($intentPayload['data_status'] ?? 'ok');
            $assistantProvider = $answerPayload['provider'] ?? null;
            $assistantModel = $answerPayload['model'] ?? null;
            $assistantSourceType = $answerPayload['source_type'] ?? ($intentPayload['source_type'] ?? 'db');
        }

        $assistantMessage = $this->chatSessionService->storeAssistantMessage(
            session: $session,
            content: $assistantContent,
            provider: $assistantProvider,
            model: $assistantModel,
            meta: [
                'intent' => $parserResult['primary_intent'],
                'resolved_entities' => $resolvedEntities,
                'source_type' => $assistantSourceType,
                'data_status' => $assistantDataStatus,
                'answer_status' => $assistantDataStatus,
                'source_scope' => 'website',
            ]
        );

        return [
            'session_id' => $session->id,
            'message_id' => $assistantMessage->id,
            'mode' => $session->mode,
            'scope' => $session->scope,
            'parser' => $parserResult,
            'resolved_entities' => $resolvedEntities,
            'quiz_resolution' => $quizResolution,
            'course_resolution' => $courseResolution,
            'intent_payload' => $intentPayload,
            'answer' => $assistantMessage->content,
            'answer_status' => $assistantDataStatus,
            'source_type' => $assistantSourceType,
        ];
    }

    public function getIntentPayload(string $intent, int $userId, array $resolvedEntities = []): array
    {
        $handler = config('services.website_assistant.intent_handlers.' . $intent);

        if (is_string($handler) && method_exists($this, $handler)) {
            return $this->{$handler}($userId, $resolvedEntities);
        }

        return [
            'intent' => $intent,
            'source_type' => 'db',
            'resolved_entities' => $resolvedEntities,
            'data' => null,
            'data_status' => 'unsupported_intent',
        ];
    }

    protected function parseQuestion(AiChatSession $session, string $message): array
    {
        $conversationHistory = $this->chatSessionService->getRecentMessages($session, 6)
            ->map(fn($chatMessage) => [
                'role' => $chatMessage->role,
                'content' => $chatMessage->content,
            ])
            ->all();

        return $this->websiteAssistantParserService->parse($message, $conversationHistory);
    }

    protected function buildResolvedEntities(array $parserResult, string $message): array
    {
        $resolvedEntities = $parserResult['entities'];
        $resolvedEntities['original_question'] = $message;
        $resolvedEntities['requested_fields'] = $parserResult['requested_fields'] ?? ['summary'];
        $resolvedEntities['reference_mode'] = $parserResult['reference_mode'] ?? 'none';

        return $resolvedEntities;
    }

    protected function resolveEntities(int $userId, string $intent, array &$resolvedEntities): array
    {
        $quizResolution = null;
        // Kiểm tra xem intent có phải là quiz_history hoặc có quiz_name thì gọi quizEntityResolverService
        if ($intent === 'quiz_history' || ! empty($resolvedEntities['quiz_name'])) {
            $quizResolution = $this->quizEntityResolverService->resolveForUser(
                $userId,
                $resolvedEntities['quiz_name'] ?? null,
                isset($resolvedEntities['course_id']) ? (int) $resolvedEntities['course_id'] : null
            );

            if (($quizResolution['status'] ?? null) === 'resolved') {
                $resolvedEntities['quiz_id'] = $quizResolution['quiz_id'];
                $resolvedEntities['quiz_name'] = $quizResolution['matched_quiz_name'];

                if (! empty($quizResolution['course_id'])) {
                    $resolvedEntities['course_id'] = $quizResolution['course_id'];
                    $resolvedEntities['course_name'] = $quizResolution['matched_course_name'];
                }
            }
        }

        $courseResolution = null;
        // Kiểm tra xem có course_name không và chưa có course_id và chưa có quiz_id thì gọi courseEntityResolverService
        if (
            ! empty($resolvedEntities['course_name'])
            && empty($resolvedEntities['course_id'])
            && empty($resolvedEntities['quiz_id'])
        ) {
            $courseResolution = $this->courseEntityResolverService->resolveForUser(
                $userId,
                $resolvedEntities['course_name']
            );

            if (($courseResolution['status'] ?? null) === 'resolved') {
                $resolvedEntities['course_id'] = $courseResolution['course_id'];
                $resolvedEntities['course_name'] = $courseResolution['matched_course_name'];
            }
        }

        return [$quizResolution, $courseResolution];
    }

    protected function getResolutionMessage(array $parserResult, ?array $quizResolution, ?array $courseResolution): ?array
    {
        if ($parserResult['needs_clarification']) {
            return [
                $parserResult['clarification_question'] ?: 'Minh can ban lam ro them cau hoi nay.',
                'needs_clarification',
            ];
        }

        if ($quizResolution && ($quizResolution['status'] ?? null) === 'ambiguous') {
            $candidateNames = collect($quizResolution['candidates'] ?? [])
                ->pluck('quiz_name')
                ->filter()
                ->implode(', ');

            return [
                $candidateNames !== ''
                    ? 'Minh thay co nhieu quiz phu hop: ' . $candidateNames . '. Ban muon hoi quiz nao?'
                    : 'Minh thay co nhieu quiz phu hop. Ban noi ro ten quiz giup minh nhe.',
                'ambiguous',
            ];
        }

        if ($quizResolution && ($quizResolution['status'] ?? null) === 'not_found') {
            return [
                'Minh chua tim thay quiz phu hop trong lich su lam bai cua ban.',
                'not_found',
            ];
        }

        if ($courseResolution && ($courseResolution['status'] ?? null) === 'ambiguous') {
            $candidateNames = collect($courseResolution['candidates'] ?? [])
                ->pluck('course_name')
                ->filter()
                ->implode(', ');

            return [
                $candidateNames !== ''
                    ? 'Minh thay co nhieu khoa phu hop: ' . $candidateNames . '. Ban muon hoi khoa nao?'
                    : 'Minh thay co nhieu khoa phu hop. Ban noi ro ten khoa giup minh nhe.',
                'ambiguous',
            ];
        }

        if ($courseResolution && ($courseResolution['status'] ?? null) === 'not_found') {
            return [
                'Minh chua tim thay khoa hoc phu hop trong danh sach khoa ban dang co.',
                'not_found',
            ];
        }

        return null;
    }

    protected function getCourseProgressPayload(int $userId, array $resolvedEntities): array
    {
        return $this->learnerInsightService->getCourseProgress($userId, (int) ($resolvedEntities['course_id'] ?? 0));
    }

    protected function getUnfinishedCoursesPayload(int $userId, array $resolvedEntities): array
    {
        return $this->learnerInsightService->getUnfinishedCourses($userId);
    }

    protected function getQuizHistoryPayload(int $userId, array $resolvedEntities): array
    {
        return $this->learnerInsightService->getQuizHistory(
            $userId,
            isset($resolvedEntities['course_id']) ? (int) $resolvedEntities['course_id'] : null,
            isset($resolvedEntities['quiz_id']) ? (int) $resolvedEntities['quiz_id'] : null
        );
    }

    protected function getCertificateStatusPayload(int $userId, array $resolvedEntities): array
    {
        return $this->learnerInsightService->getCertificateStatus(
            $userId,
            isset($resolvedEntities['course_id']) ? (int) $resolvedEntities['course_id'] : null
        );
    }

    protected function getRefundStatusPayload(int $userId, array $resolvedEntities): array
    {
        return $this->learnerRefundService->getRefundStatus(
            $userId,
            isset($resolvedEntities['course_id']) ? (int) $resolvedEntities['course_id'] : null,
            $resolvedEntities['order_reference'] ?? null
        );
    }

    protected function getFeatureHowToPayload(int $userId, array $resolvedEntities): array
    {
        return array_merge(
            $this->websiteKnowledgeService->getFeatureHowTo(
                $resolvedEntities['original_question'] ?? '',
                $resolvedEntities['feature_name'] ?? null
            ),
            [
                'resolved_entities' => $resolvedEntities,
            ]
        );
    }
}
