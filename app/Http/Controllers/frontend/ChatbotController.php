<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\ChatbotAskRequest;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Services\ChatSessionService;
use App\Services\GeminiChatService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Throwable;

class ChatbotController extends Controller
{
    protected $geminiChatService;
    protected $chatSessionService;
    public function __construct(GeminiChatService $geminiChatService, ChatSessionService $chatSessionService)
    {
        $this->geminiChatService = $geminiChatService;
        $this->chatSessionService = $chatSessionService;
    }

    public function ask(ChatbotAskRequest $request): JsonResponse
    {
        try {
            $user = Auth::user();

            $course = Course::query()->findOrFail($request->integer('course_id'));
            $lecture = CourseLecture::query()->findOrFail($request->integer('lecture_id'));

            // Kiểm tra lecture có thuộc course không
            if ((int) $lecture->course_id !== (int) $course->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lecture không thuộc course đã chọn.',
                ], 422);
            }

            if (!method_exists($user, 'hasAccessToCourse') || !$user->hasAccessToCourse($course)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập khóa học này.',
                ], 403);
            }

            $session = $this->chatSessionService->getOrCreateSession(
                userId: (int) $user->id,
                courseId: (int) $course->id,
                lectureId: (int) $lecture->id
            );

            // Lấy lịch sử 10 tin nhắn gần nhất để AI có ngữ cảnh
            $history = $this->chatSessionService->getMessagesForSession($session, 10);
            
            $formattedHistory = $history->map(function ($msg) {
                return [
                    'role' => $msg->role,
                    'content' => $msg->content,
                ];
            })->toArray();

            $userMessage = $request->string('message')->toString();

            // Lưu tin nhắn của user TRƯỚC khi gọi AI
            $this->chatSessionService->storeUserMessage(
                session: $session,
                userId: (int) $user->id,
                content: $userMessage
            );

            $result = $this->geminiChatService->ask(
                message: $userMessage,
                course: $course,
                lecture: $lecture,
                userId: (int) $user->id,
                history: $formattedHistory
            );

            $assistantMessage = $this->chatSessionService->storeAssistantMessage(
                session: $session,
                content: $result['answer'],
                provider: $result['provider'] ?? 'gemini',
                model: $result['model'] ?? null,
                meta: [
                    'latency_ms' => $result['latency_ms'] ?? null,
                ]
            );

            return response()->json([
                'success' => true,
                'message' => 'Chatbot trả lời thành công.',
                'data' => [
                    'session_id' => $session->id,
                    'answer' => $assistantMessage->content,
                    'assistant_message_id' => $assistantMessage->id,
                    'model' => $result['model'],
                    'provider' => $result['provider'],
                ],
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi xử lý câu hỏi.',
                'debug' => app()->environment('local') ? [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
            ], 500);
        }
    }

    public function history(): JsonResponse
    {
        try {
            $user = Auth::user();
            $courseId = (int) request('course_id');
            $lectureId = (int) request('lecture_id');

            $course = Course::query()->findOrFail($courseId);
            $lecture = CourseLecture::query()->findOrFail($lectureId);

            if ((int) $lecture->course_id !== (int) $course->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lecture không thuộc course đã chọn.',
                ], 422);
            }

            if (!method_exists($user, 'hasAccessToCourse') || !$user->hasAccessToCourse($course)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền truy cập khóa học này.',
                ], 403);
            }

            $session = \App\Models\AiChatSession::query()
                ->where('user_id', $user->id)
                ->where('course_id', $course->id)
                ->where('lecture_id', $lecture->id)
                ->where('status', 'active')
                ->latest('id')
                ->first();

            if (!$session) {
                return response()->json([
                    'success' => true,
                    'message' => 'Chưa có lịch sử chat.',
                    'data' => [
                        'session_id' => null,
                        'messages' => [],
                    ],
                ]);
            }

            $messages = $this->chatSessionService->getMessagesForSession($session, 50);

            return response()->json([
                'success' => true,
                'message' => 'Lấy lịch sử chat thành công.',
                'data' => [
                    'session_id' => $session->id,
                    'messages' => $messages->map(function ($message) {
                        return [
                            'id' => $message->id,
                            'role' => $message->role,
                            'content' => $message->content,
                            'provider' => $message->provider,
                            'model' => $message->model,
                            'created_at' => $message->created_at?->toDateTimeString(),
                        ];
                    })->values(),
                ],
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Không thể tải lịch sử chat.',
                'debug' => app()->environment('local') ? [
                    'error' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ] : null,
            ], 500);
        }
    }
}
