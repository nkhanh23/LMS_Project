<?php

namespace App\Services;

use App\Services\Contracts\AIProviderInterface;

class WebsiteAssistantAnswerService
{
    public function __construct(
        protected AIProviderInterface $aiProvider
    ) {}

    public function generate(string $userQuestion, array $intentPayload): array
    {
        $intent = $intentPayload['intent'] ?? 'unknown';
        $dataStatus = $intentPayload['data_status'] ?? 'ok';

        if ($dataStatus !== 'ok') {
            return [
                'content' => $this->buildMissingDataContent($intent, $dataStatus),
                'provider' => null,
                'model' => null,
                'source_type' => $intentPayload['source_type'] ?? 'db',
                'answer_status' => $dataStatus,
            ];
        }

        return match ($intent) {
            'course_progress' => $this->buildTemplateResponse(
                $this->formatCourseProgress($intentPayload['data'] ?? []),
                $intentPayload['source_type'] ?? 'db'
            ),
            'certificate_status' => $this->buildTemplateResponse(
                $this->formatCertificateStatus($intentPayload['data'] ?? [], $intentPayload['resolved_entities'] ?? []),
                $intentPayload['source_type'] ?? 'db'
            ),
            'refund_status' => $this->buildTemplateResponse(
                $this->formatRefundStatus($intentPayload['data'] ?? []),
                $intentPayload['source_type'] ?? 'db'
            ),
            'quiz_history' => $this->buildTemplateResponse(
                $this->formatQuizHistory($intentPayload['data'] ?? []),
                $intentPayload['source_type'] ?? 'db'
            ),
            'unfinished_courses' => $this->generateFromAi($userQuestion, $intentPayload, 0.1),
            'feature_how_to' => $this->generateFeatureHowTo($userQuestion, $intentPayload),
            default => $this->buildTemplateResponse(
                'Mình chưa có đủ dữ liệu để trả lời câu hỏi này một cách chính xác.',
                $intentPayload['source_type'] ?? 'db',
                'unsupported_intent'
            ),
        };
    }

    protected function buildTemplateResponse(
        string $content,
        string $sourceType,
        string $answerStatus = 'ok'
    ): array {
        return [
            'content' => $content,
            'provider' => null,
            'model' => null,
            'source_type' => $sourceType,
            'answer_status' => $answerStatus,
        ];
    }

    protected function generateFromAi(string $userQuestion, array $intentPayload, float $temperature): array
    {
        $prompt = $this->buildAnswerPrompt($userQuestion, $intentPayload);
        $answerPayload = $this->aiProvider->generateAnswer($prompt, [
            'temperature' => $temperature,
            'max_output_tokens' => 500,
        ]);

        $content = trim((string) ($answerPayload['answer'] ?? ''));

        if ($content === '') {
            $content = 'Mình chưa thể tạo câu trả lời phù hợp lúc này.';
        }

        return [
            'content' => $content,
            'provider' => $answerPayload['provider'] ?? null,
            'model' => $answerPayload['model'] ?? null,
            'source_type' => $intentPayload['source_type'] ?? 'db',
            'answer_status' => 'ok',
        ];
    }

    protected function generateFeatureHowTo(string $userQuestion, array $intentPayload): array
    {
        $prompt = $this->buildFeatureHowToPrompt($userQuestion, $intentPayload);
        $answerPayload = $this->aiProvider->generateAnswer($prompt, [
            'temperature' => 0.1,
            'max_output_tokens' => 500,
        ]);

        $content = trim((string) ($answerPayload['answer'] ?? ''));

        if ($content === '') {
            $content = 'Mình chưa thể tạo câu trả lời phù hợp lúc này.';
        }

        return [
            'content' => $content,
            'provider' => $answerPayload['provider'] ?? null,
            'model' => $answerPayload['model'] ?? null,
            'source_type' => $intentPayload['source_type'] ?? 'kb',
            'answer_status' => 'ok',
        ];
    }

    protected function buildAnswerPrompt(string $userQuestion, array $intentPayload): string
    {
        $intent = $intentPayload['intent'] ?? 'unknown';
        $resolvedEntities = json_encode($intentPayload['resolved_entities'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $context = json_encode($intentPayload['data'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Bạn là trợ lý LMS cho người học.

Chỉ được trả lời dựa trên CONTEXT backend cung cấp.
Không được bịa thêm dữ liệu hoặc hướng dẫn ngoài CONTEXT.
Nếu CONTEXT không đủ, phải nói rõ là chưa đủ dữ liệu.
Không nhắc tới parser, JSON, backend, prompt, mô hình.
Trả lời bằng tiếng Việt, ngắn gọn, rõ ràng, đúng trọng tâm.

Ưu tiên:
- Nếu là dữ liệu cá nhân: trả lời trực tiếp bằng đúng số liệu/trạng thái trong CONTEXT.
- Nếu là how-to: hướng dẫn đúng theo CONTEXT, không thêm bước ngoài CONTEXT.
- Nếu là danh sách: tóm tắt trước, liệt kê sau.
- Nếu không có dữ liệu: nói rõ hiện chưa có dữ liệu phù hợp.

USER_QUESTION:
{$userQuestion}

INTENT:
{$intent}

RESOLVED_ENTITIES:
{$resolvedEntities}

CONTEXT:
{$context}
PROMPT;
    }

    protected function buildFeatureHowToPrompt(string $userQuestion, array $intentPayload): string
    {
        $data = $intentPayload['data'] ?? [];
        $resolvedEntities = json_encode($intentPayload['resolved_entities'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $primaryDocument = json_encode($data['primary_document'] ?? null, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $documents = json_encode($data['documents'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $priorityRule = json_encode($data['doc_priority_rule'] ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        return <<<PROMPT
Bạn là trợ lý LMS cho người học.

Đây là câu hỏi how-to về hệ thống.
Chỉ được trả lời dựa trên dữ liệu KB backend cung cấp.
Không được bịa bước hoặc thêm hướng dẫn ngoài tài liệu.
Nếu PRIMARY_DOCUMENT đã đủ ý, ưu tiên dùng nó trước.
Chỉ dùng tài liệu phụ khi PRIMARY_DOCUMENT chưa đủ rõ.
Ưu tiên tài liệu theo thứ tự: feature_how_to > faq > policy.
Nếu KB không đủ dữ liệu, phải nói rõ là chưa có hướng dẫn phù hợp.
Trả lời bằng tiếng Việt, ngắn gọn, rõ ràng, theo từng bước khi phù hợp.

USER_QUESTION:
{$userQuestion}

RESOLVED_ENTITIES:
{$resolvedEntities}

DOC_PRIORITY_RULE:
{$priorityRule}

PRIMARY_DOCUMENT:
{$primaryDocument}

SUPPORTING_DOCUMENTS:
{$documents}
PROMPT;
    }

    protected function buildMissingDataContent(string $intent, string $dataStatus): string
    {
        return match ($dataStatus) {
            'pending_kb_pipeline' => 'Mình chưa có đủ dữ liệu hướng dẫn hệ thống để trả lời chính xác câu hỏi này.',
            'not_found' => match ($intent) {
                'course_progress' => 'Mình chưa tìm thấy khóa học phù hợp để kiểm tra tiến độ.',
                'feature_how_to' => 'Mình chưa tìm thấy tài liệu hướng dẫn phù hợp cho câu hỏi này.',
                default => 'Mình chưa tìm thấy dữ liệu phù hợp để trả lời câu hỏi này.',
            },
            default => 'Mình chưa có đủ dữ liệu để trả lời chính xác câu hỏi này.',
        };
    }

    protected function formatCourseProgress(array $data): string
    {
        if (empty($data['course_title'])) {
            return 'Mình chưa có đủ dữ liệu để trả lời tiến độ khóa học này.';
        }

        $lastLectureTitle = $data['last_lecture']['title'] ?? null;
        $base = 'Bạn đã hoàn thành '
            . (int) ($data['completion_percent'] ?? 0)
            . '% khóa '
            . $data['course_title']
            . ', tương đương '
            . (int) ($data['completed_lectures'] ?? 0)
            . '/'
            . (int) ($data['total_lectures'] ?? 0)
            . ' bài học.';

        if ($lastLectureTitle) {
            $base .= ' Bài gần nhất bạn học là ' . $lastLectureTitle . '.';
        }

        return $base;
    }

    protected function formatCertificateStatus(array $data, array $resolvedEntities): string
    {
        $courses = collect($data['courses'] ?? []);

        if (! empty($resolvedEntities['course_id'])) {
            $course = $courses->firstWhere('course_id', (int) $resolvedEntities['course_id']);

            if (! $course) {
                return 'Mình chưa tìm thấy dữ liệu chứng chỉ cho khóa học này.';
            }

            if (! empty($course['is_eligible'])) {
                return 'Bạn đã đủ điều kiện nhận chứng chỉ cho khóa ' . $course['title'] . '.';
            }

            return 'Bạn chưa đủ điều kiện nhận chứng chỉ cho khóa '
                . $course['title']
                . ' vì hiện mới hoàn thành '
                . (int) ($course['completion_percent'] ?? 0)
                . '%.';
        }

        $eligibleCount = (int) ($data['summary']['eligible_courses_count'] ?? 0);
        $totalCount = (int) ($data['summary']['certificate_courses_count'] ?? 0);

        if ($totalCount === 0) {
            return 'Hiện tại bạn chưa có khóa học nào hỗ trợ chứng chỉ.';
        }

        return 'Bạn có ' . $eligibleCount . '/' . $totalCount . ' khóa học đã đủ điều kiện nhận chứng chỉ.';
    }

    protected function formatRefundStatus(array $data): string
    {
        $recentRequests = collect($data['recent_requests'] ?? []);

        if ($recentRequests->isEmpty()) {
            return 'Hiện tại bạn chưa có yêu cầu hoàn tiền nào.';
        }

        $latest = $recentRequests->first();

        return 'Yêu cầu hoàn tiền gần nhất của bạn cho khóa '
            . ($latest['course_title'] ?? 'không rõ khóa học')
            . ' hiện ở trạng thái '
            . ($latest['status'] ?? 'không rõ')
            . '.';
    }

    protected function formatQuizHistory(array $data): string
    {
        $selectedQuiz = $data['selected_quiz'] ?? null;

        if (is_array($selectedQuiz) && ! empty($selectedQuiz['quiz_title'])) {
            $attemptsUsed = (int) ($selectedQuiz['attempts_used'] ?? 0);
            $message = 'Ban da lam ' . $attemptsUsed . ' luot cho quiz ' . $selectedQuiz['quiz_title'] . '.';

            if (($selectedQuiz['max_attempts'] ?? null) === null) {
                $message .= ' Quiz nay hien khong gioi han so luot lam.';
            } else {
                $message .= ' Ban con ' . (int) ($selectedQuiz['remaining_attempts'] ?? 0) . ' luot de lam quiz nay.';
            }

            if (($selectedQuiz['latest_score'] ?? null) !== null) {
                $message .= ' Diem gan nhat cua ban la ' . (int) $selectedQuiz['latest_score'] . '.';
            }

            return $message;
        }

        $totalAttempts = (int) ($data['summary']['total_attempts'] ?? 0);
        $recentAttempts = collect($data['recent_attempts'] ?? []);

        if ($totalAttempts === 0) {
            return 'Hiện tại bạn chưa có lượt làm quiz nào.';
        }

        $latest = $recentAttempts->first();
        $message = 'Bạn đã làm tổng cộng ' . $totalAttempts . ' lượt quiz.';

        if ($latest) {
            $message .= ' Lần gần nhất là quiz '
                . ($latest['quiz_title'] ?? 'không rõ tên')
                . ' với điểm '
                . (int) ($latest['score'] ?? 0)
                . '.';
        }

        return $message;
    }
}
