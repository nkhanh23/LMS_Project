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
        if (! $this->geminiConfigService->isEnabled()) {
            return response()->json([
                'success' => false,
                'message' => 'Chatbot AI hiện đang được tắt bởi quản trị viên.',
            ], 503);
        }

        try {
            [$user, $course, $lecture] = $this->resolveAccessibleContext($request);

            $result = $this->aiChatOrchestratorService->handle(
                userId: (int) $user->id,
                course: $course,
                lecture: $lecture,
                question: trim($request->string('message')->toString())
            );

            return response()->json([
                'success' => true,
                'message' => $this->buildTopLevelMessage($result['answer_status']),
                'data' => $result,
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Không thể xử lý câu hỏi lúc này.',
            ], 500);
        }
    }

    public function history(ChatbotAskRequest $request): JsonResponse
    {
        try {
            [$user, $course, $lecture] = $this->resolveAccessibleContext($request);

            $session = $this->chatSessionService->getOrCreateSession(
                userId: (int) $user->id,
                courseId: (int) $course->id,
                lectureId: (int) $lecture->id
            );

            $messages = $this->chatSessionService->getStructuredHistory($session, 50);

            return response()->json([
                'success' => true,
                'data' => [
                    'session_id' => $session->id,
                    'course_id' => $course->id,
                    'lecture_id' => $lecture->id,
                    'session_status' => $session->status,
                    'last_activity_at' => optional($session->last_activity_at)->toDateTimeString(),
                    'messages' => $messages,
                ],
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Không thể tải lịch sử chat lúc này.',
            ], 500);
        }
    }

    public function newSession(ChatbotAskRequest $request): JsonResponse
    {
        try {
            [$user, $course, $lecture] = $this->resolveAccessibleContext($request);

            $session = $this->chatSessionService->createNewSessionForMode(
                mode: 'lesson',
                userId: (int) $user->id,
                courseId: (int) $course->id,
                lectureId: (int) $lecture->id
            );

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo phiên chat mới cho bài học này.',
                'data' => [
                    'session_id' => $session->id,
                    'course_id' => $course->id,
                    'lecture_id' => $lecture->id,
                    'mode' => $session->mode,
                    'scope' => $session->scope,
                    'session_status' => $session->status,
                    'last_activity_at' => optional($session->last_activity_at)->toDateTimeString(),
                    'messages' => [],
                ],
            ]);
        } catch (Throwable $e) {
            report($e);

            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo phiên chat mới lúc này.',
            ], 500);
        }
    }

    private function resolveAccessibleContext(ChatbotAskRequest $request): array
    {
        $user = Auth::user();

        $course = Course::query()->findOrFail($request->integer('course_id'));
        $lecture = CourseLecture::query()->findOrFail($request->integer('lecture_id'));

        if ((int) $lecture->course_id !== (int) $course->id) {
            abort(response()->json([
                'success' => false,
                'message' => 'Lesson không thuộc course đã chọn.',
            ], 422));
        }

        if (! method_exists($user, 'hasAccessToCourse') || ! $user->hasAccessToCourse($course)) {
            abort(response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền truy cập khóa học này.',
            ], 403));
        }

        return [$user, $course, $lecture];
    }

    private function buildTopLevelMessage(string $answerStatus): string
    {
        return match ($answerStatus) {
            'success' => 'AI đã trả lời thành công.',
            'weak_evidence' => 'AI đã trả lời, nhưng evidence còn yếu.',
            'no_evidence' => 'Không tìm thấy evidence đủ mạnh trong tài liệu hiện có.',
            'provider_error' => 'AI provider đang lỗi tạm thời.',
            default => 'Yêu cầu đã được xử lý.',
        };
    }
}
