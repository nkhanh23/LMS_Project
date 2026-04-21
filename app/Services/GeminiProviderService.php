<?php

namespace App\Services;

use App\Services\Contracts\AIProviderInterface;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class GeminiProviderService implements AIProviderInterface
{
    public function __construct(
        protected GeminiConfigService $geminiConfigService
    ) {}

    public function generateAnswer(string $prompt, array $options = []): array
    {
        $config = $this->geminiConfigService->getConfig();

        if (!($config['enabled'] ?? false)) {
            throw new RuntimeException('Chatbot AI hiện đang tắt.');
        }

        if (empty($config['api_key'])) {
            throw new RuntimeException('Chưa cấu hình Gemini API key.');
        }

        $model = $options['model'] ?? $config['model'];
        $timeout = (int) ($options['timeout'] ?? $config['timeout']);
        $temperature = (float) ($options['temperature'] ?? $config['temperature']);
        $maxOutputTokens = (int) ($options['max_output_tokens'] ?? $config['max_output_tokens']);

        $baseUrl = rtrim($config['base_url'], '/');
        $url = "{$baseUrl}/models/{$model}:generateContent?key={$config['api_key']}";

        Log::debug('Gemini API Request', [
            'url_masked' => "{$baseUrl}/models/{$model}:generateContent?key=***",
            'model' => $model
        ]);

        try {
            $response = Http::timeout($timeout)
                ->retry(2, 500, function (Throwable $exception) {
                    return true;
                })
                ->acceptJson()
                ->post($url, [
                    'contents' => [
                        [
                            'parts' => [
                                ['text' => $prompt],
                            ],
                        ],
                    ],
                    'generationConfig' => [
                        'temperature' => $temperature,
                        'maxOutputTokens' => $maxOutputTokens,
                    ],
                ]);

            if (!$response->successful()) {
                Log::warning('Gemini API request failed', [
                    'provider' => 'gemini',
                    'status' => $response->status(),
                    'config' => $this->geminiConfigService->getMaskedConfig(),
                ]);

                throw new RuntimeException('Gemini API error: ' . $response->status());
            }

            $json = $response->json();

            $answer = data_get($json, 'candidates.0.content.parts.0.text');

            if (!$answer) {
                Log::warning('Gemini returned empty answer', [
                    'provider' => 'gemini',
                    'config' => $this->geminiConfigService->getMaskedConfig(),
                ]);

                throw new RuntimeException('Gemini trả về nội dung rỗng.');
            }

            return [
                'provider' => 'gemini',
                'model' => $model,
                'answer' => $answer,
                'finish_reason' => data_get($json, 'candidates.0.finishReason'),
                'raw' => $json,
            ];
        } catch (Throwable $e) {
            Log::error('Gemini provider exception', [
                'provider' => 'gemini',
                'error' => $e->getMessage(),
                'config' => $this->geminiConfigService->getMaskedConfig(),
            ]);

            throw $e;
        }
    }
}
