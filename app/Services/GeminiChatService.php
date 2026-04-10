<?php

namespace App\Services;

/**
 * @deprecated Dịch vụ này đã lỗi thời, vui lòng sử dụng AIProviderInterface/GeminiProviderService.
 * Lớp này được giữ lại như một lớp bọc (wrapper) để đảm bảo tính tương thích ngược.
 */
class GeminiChatService
{
    public function __construct(
        protected GeminiProviderService $geminiProviderService
    ) {}

    /**
     * @param string $prompt
     * @return array
     */
    public function ask(string $prompt): array
    {
        return $this->geminiProviderService->generateAnswer($prompt);
    }
}
