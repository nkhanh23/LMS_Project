<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatbotAskRequest;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Services\AiChatOrchestratorService;
use App\Services\ChatSessionService;
use App\Services\GeminiChatService;
use App\Services\GeminiConfigService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ChatbotController extends Controller
{
    protected $geminiChatService;
    protected $chatSessionService;
    protected $aiChatOrchestratorService;
    protected $geminiConfigService;
    public function __construct(GeminiChatService $geminiChatService, ChatSessionService $chatSessionService, AiChatOrchestratorService $aiChatOrchestratorService, GeminiConfigService $geminiConfigService)
    {
        $this->geminiChatService = $geminiChatService;
        $this->chatSessionService = $chatSessionService;
        $this->aiChatOrchestratorService = $aiChatOrchestratorService;
        $this->geminiConfigService = $geminiConfigService;
    }

    public function ask(ChatbotAskRequest $request): JsonResponse
    {
        if (!$this->geminiConfigService->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Chatbot AI hiện đang được tắt bởi quản trị viên.',
            ], 503);
        }

        try {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            $course = Course::query()->findOrFail($request->integer('course_id'));
            $lecture = CourseLecture::query()->findOrFail($request->integer('lecture_id'));

            if ((int) $lecture->course_id !== (int) $course->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lesson không thuộc course đã chọn.',
                ], 422);
            }

            if (!method_exists($user, 'hasAccessToCourse') || !$user->hasAccessToCourse($course)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập khóa học này.',
                ], 403);
            }

            $result = $this->aiChatOrchestratorService->handle(
                userId: (int) $user->id,
                course: $course,
                lecture: $lecture,
                question: $request->string('message')->toString()
            );

            return response()->json([
                'success' => true,
                'message' => 'AI đã trả lời thành công.',
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Không thể xử lý câu hỏi AI lúc này.',
            ], 500);
        }
    }

    public function history(ChatbotAskRequest $request): JsonResponse
    {
        $user = Auth::user();

        $session = $this->chatSessionService->getOrCreateSession(
            userId: (int) $user->id,
            courseId: $request->integer('course_id'),
            lectureId: $request->integer('lecture_id')
        );

        $messages = $this->chatSessionService->getSessionMessagesWithCitations($session)
            ->map(function ($message) {
                return [
                    'id' => $message->id,
                    'role' => $message->role,
                    'content' => $message->content,
                    'citations' => $message->citations->map(function ($citation) {
                        return [
                            'document_title' => $citation->document?->title,
                            'chunk_id' => $citation->chunk_id,
                            'snippet' => $citation->snippet,
                            'rank' => $citation->rank,
                        ];
                    })->values(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => [
                'session_id' => $session->id,
                'messages' => $messages,
            ],
        ]);
    }
}
