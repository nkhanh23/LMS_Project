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
        string $lectureTitle,
        string $evidenceStrength = 'enough',
        string $sourceScope = 'lesson'
    ): string {
        $historyText = $this->buildHistoryBlock($history);
        $evidenceText = $this->buildEvidenceBlock($chunks);
        $answerFormatText = $this->buildAnswerFormatBlock($evidenceStrength);

        return <<<PROMPT
[VAI TRÒ]
Bạn là gia sư ảo của nền tảng E-learning.

[NGỮ CẢNH]
- Khóa học hiện tại: {$courseTitle}
- Bài học hiện tại: {$lectureTitle}
- Phạm vi retrieval: {$sourceScope}
- Mức độ evidence: {$evidenceStrength}

[QUY TẮC BẮT BUỘC]
1. Chỉ được trả lời dựa trên phần EVIDENCE được cung cấp.
2. Không được bịa, suy đoán, hoặc thêm kiến thức ngoài EVIDENCE.
3. Nếu evidence không đủ mạnh, phải nói rõ mức độ chưa chắc chắn.
4. Nếu không tìm thấy thông tin trực tiếp trong EVIDENCE, phải nói rõ là không tìm thấy trong tài liệu hiện có.
5. Không được trả lời như thể bạn biết toàn bộ khóa học nếu evidence chỉ nằm trong 1 lesson.
6. Trả lời bằng tiếng Việt, rõ ràng, dễ hiểu, ưu tiên ngắn gọn.
7. Không hiển thị dòng "Nguồn tham khảo" hoặc danh sách [Nguồn ...] trong câu trả lời.

[LỊCH SỬ HỘI THOẠI GẦN ĐÂY]
{$historyText}

[EVIDENCE]
{$evidenceText}

[CÂU HỎI CỦA HỌC VIÊN]
{$question}

[ĐỊNH DẠNG CÂU TRẢ LỜI]
{$answerFormatText}
PROMPT;
    }

    private function buildHistoryBlock(Collection $history): string
    {
        if ($history->isEmpty()) {
            return '(Chưa có lịch sử hội thoại gần đây)';
        }

        return $history
            ->take(6)
            ->map(function ($msg) {
                return strtoupper((string) $msg->role) . ': ' . trim((string) $msg->content);
            })
            ->implode("\n");
    }

    private function buildEvidenceBlock(Collection $chunks): string
    {
        if ($chunks->isEmpty()) {
            return '(Không có evidence được retrieve)';
        }

        return $chunks
            ->values()
            ->map(function ($chunk, $i) {
                $docTitle = $chunk->document?->title ?? 'Unknown document';

                return "[Nguồn " . ($i + 1) . "]\n"
                    . "Document: {$docTitle}\n"
                    . "Chunk ID: {$chunk->id}\n"
                    . "Content: " . trim((string) $chunk->content);
            })
            ->implode("\n\n");
    }

    private function buildAnswerFormatBlock(string $evidenceStrength): string
    {
        $warningRule = $evidenceStrength === 'weak'
            ? '- Mở đầu bằng 1 câu cảnh báo ngắn rằng evidence hiện còn yếu hoặc chưa đủ mạnh để khẳng định chắc chắn.'
            : '- Không cần cảnh báo nếu evidence đủ mạnh.';

        return <<<TEXT
- Phần 1: Trả lời trực tiếp vào câu hỏi.
{$warningRule}
- Phần 2: Nếu phù hợp, giải thích ngắn gọn theo đúng ngữ cảnh bài học hiện tại.
- Phần 3: Kết thúc tự nhiên, không thêm dòng nguồn tham khảo.
- Không dùng markdown phức tạp.
- Không nói rằng bạn là AI model hoặc đang dùng prompt nội bộ.
TEXT;
    }
}
