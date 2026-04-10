<?php

namespace App\Services;

use Illuminate\Support\Collection;

class AiPromptBuilderService
{
    public function build(
        string $question,
        Collection $history,
        Collection $chunks,
        string $courseTitle,
        string $lectureTitle
    ): string {
        $historyText = $history->map(function ($msg) {
            return strtoupper($msg->role) . ': ' . $msg->content;
        })->implode("\n");

        $evidenceText = $chunks->map(function ($chunk, $i) {
            $docTitle = $chunk->document?->title ?? 'Unknown document';

            return "[Nguồn " . ($i + 1) . "] "
                . "Document: {$docTitle}\n"
                . "Chunk ID: {$chunk->id}\n"
                . "Content: {$chunk->content}";
        })->implode("\n\n");

        return <<<PROMPT
Bạn là gia sư AI của hệ thống LMS.

Nguyên tắc trả lời:
1. Chỉ trả lời dựa trên dữ liệu được cung cấp bên dưới.
2. Ưu tiên nội dung thuộc bài học hiện tại.
3. Nếu dữ liệu không đủ chắc chắn, hãy nói rõ là không tìm thấy đủ thông tin trong tài liệu bài học/khóa học.
4. Trả lời ngắn gọn, rõ ràng, dễ hiểu cho học viên.
5. Cuối câu trả lời, thêm mục "Nguồn tham khảo" theo dạng [Nguồn 1], [Nguồn 2] nếu có.

Ngữ cảnh:
- Course: {$courseTitle}
- Lesson: {$lectureTitle}

Lịch sử chat gần đây:
{$historyText}

Dữ liệu truy xuất:
{$evidenceText}

Câu hỏi của học viên:
{$question}
PROMPT;
    }
}
