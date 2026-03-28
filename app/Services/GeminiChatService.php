<?php

namespace App\Services;

use App\Models\Course;
use App\Models\CourseLecture;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;

class GeminiChatService
{
    public function ask(string $message, Course $course, CourseLecture $lecture, int $userId, array $history = []): array
    {
        $apiKey = config('services.gemini.api_key');
        $model = config('services.gemini.model', 'gemini-2.0-flash');
        $baseUrl = rtrim(config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1beta'), '/');
        $timeout = (int) config('services.gemini.timeout', 45); // Tăng timeout cho các câu hỏi dài

        if (!$apiKey) {
            throw new RuntimeException('Thiếu GEMINI_API_KEY trong file .env');
        }

        $courseTitle = $course->course_title
            ?? $course->course_name
            ?? $course->title
            ?? ('Course #' . $course->id);

        $lectureTitle = $lecture->lecture_title
            ?? $lecture->title
            ?? ('Lecture #' . $lecture->id);

        // Build System Instructions (as a special first message or system_instruction if supported)
        $systemInstructions = "Bạn là gia sư AI cho nền tảng e-learning StackLearn.
Nhiệm vụ: Trả lời câu hỏi của học viên một cách chính xác, thân thiện và giàu tính giáo dục.
Quy tắc:
- Trả lời bằng tiếng Việt.
- Luôn hoàn thành câu trả lời, không dừng lại giữa chừng.
- Nếu câu hỏi liên quan đến nội dung bài học, hãy giải thích chi tiết.
- Hiện tại bạn đang hỗ trợ học viên ID {$userId} trong bài học '{$lectureTitle}' của khóa học '{$courseTitle}'.";

        $contents = [];

        // Thêm lịch sử nếu có
        foreach ($history as $msg) {
            $contents[] = [
                'role' => $msg['role'] === 'user' ? 'user' : 'model',
                'parts' => [['text' => $msg['content']]],
            ];
        }

        // Thêm message hiện tại (kèm system instruction trong prompt nếu model chưa hỗ trợ system_instruction riêng)
        $contents[] = [
            'role' => 'user',
            'parts' => [['text' => "Hệ thống: {$systemInstructions}\n\nHọc viên hỏi: {$message}"]]
        ];

        $url = "{$baseUrl}/models/{$model}:generateContent?key={$apiKey}";

        $start = microtime(true);

        $response = Http::timeout($timeout)
            ->acceptJson()
            ->post($url, [
                'contents' => $contents,
                'generationConfig' => [
                    'temperature' => 0.7, // Tăng độ sáng tạo một chút
                    'maxOutputTokens' => 2048, // Tăng giới hạn để không bị ngắt quãng
                    'topP' => 0.95,
                    'topK' => 40,
                ],
                'safetySettings' => [
                    ['category' => 'HARM_CATEGORY_HARASSMENT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_HATE_SPEECH', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_SEXUALLY_EXPLICIT', 'threshold' => 'BLOCK_NONE'],
                    ['category' => 'HARM_CATEGORY_DANGEROUS_CONTENT', 'threshold' => 'BLOCK_NONE'],
                ],
            ]);

        $latencyMs = (int) round((microtime(true) - $start) * 1000);

        if ($response->failed()) {
            Log::error('Gemini API error', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new RuntimeException(
                'Lỗi kết nối AI. Vui lòng thử lại sau.'
            );
        }

        $data = $response->json();
        $answer = data_get($data, 'candidates.0.content.parts.0.text');

        if (!$answer) {
            // Kiểm tra xem có bị chặn bởi Safety không
            $finishReason = data_get($data, 'candidates.0.finishReason');
            if ($finishReason === 'SAFETY') {
                return [
                    'answer' => 'Xin lỗi, câu hỏi này vi phạm chính sách an toàn của AI nên tôi không thể trả lời.',
                    'model' => $model,
                    'provider' => 'gemini',
                    'latency_ms' => $latencyMs,
                ];
            }
            
            throw new RuntimeException('AI không trả về nội dung. (Reason: ' . $finishReason . ')');
        }

        return [
            'answer' => trim($answer),
            'model' => $model,
            'provider' => 'gemini',
            'latency_ms' => $latencyMs,
            'raw' => $data,
        ];
    }
}
