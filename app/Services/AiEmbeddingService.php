<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class AiEmbeddingService
{
    public function __construct(
        protected GeminiConfigService $geminiConfigService
    ) {}

    public function embedDocumentChunk(string $text, ?string $title = null): array
    {
        return $this->embed(
            text: $text,
            taskType: config('services.gemini.embedding_task_type', 'RETRIEVAL_DOCUMENT'),
            title: $title
        );
    }

    public function embedQuery(string $text): array
    {
        return $this->embed(
            text: $text,
            taskType: 'RETRIEVAL_QUERY',
            title: null
        );
    }

    protected function embed(string $text, string $taskType, ?string $title = null): array
    {
        $config = $this->geminiConfigService->getConfig();

        if (!(bool) ($config['embedding_enabled'] ?? true)) {
            throw new RuntimeException('Gemini embedding đang tắt.');
        }

        $apiKey = $config['api_key'] ?? null;
        if (!$apiKey) {
            throw new RuntimeException('Chưa cấu hình Gemini API key cho embedding.');
        }

        $model = $config['embedding_model'] ?? 'gemini-embedding-2-preview';
        $dimension = (int) ($config['embedding_dimension'] ?? 768);
        $baseUrl = rtrim((string) ($config['embedding_base_url'] ?? 'https://generativelanguage.googleapis.com/v1beta'), '/');

        $payload = [
            'content' => [
                'parts' => [
                    ['text' => $text],
                ],
            ],
            'taskType' => $taskType,
            'outputDimensionality' => $dimension,
        ];

        if ($title) {
            $payload['title'] = $title;
        }

        $response = Http::timeout((int) ($config['timeout'] ?? 30))
            ->post("{$baseUrl}/models/{$model}:embedContent?key={$apiKey}", $payload)
            ->throw()
            ->json();

        $values = data_get($response, 'embedding.values');

        if (!is_array($values) || empty($values)) {
            throw new RuntimeException('Không nhận được embedding vector hợp lệ từ Gemini.');
        }

        return [
            'provider' => 'gemini',
            'model' => $model,
            'dimension' => $dimension,
            'values' => array_map(fn($v) => (float) $v, $values),
        ];
    }
}
