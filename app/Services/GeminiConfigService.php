<?php

namespace App\Services;

use App\Models\GeminiSetting;

class GeminiConfigService
{
    public function getConfig(): array
    {
        return [
            'api_key' => config('services.gemini.api_key'),
            'model' => config('services.gemini.model', 'gemini-1.5-flash'),
            'timeout' => (int) config('services.gemini.timeout', 30),
            'temperature' => (float) config('services.gemini.temperature', 0.2),
            'max_output_tokens' => (int) config('services.gemini.max_output_tokens', 1024),
            'enabled' => (bool) config('services.gemini.enabled', true),
            'base_url' => config('services.gemini.base_url', 'https://generativelanguage.googleapis.com/v1'),
        ];
    }

    public function isEnabled(): bool
    {
        return (bool) ($this->getConfig()['enabled'] ?? false);
    }

    public function getMaskedConfig(): array
    {
        $config = $this->getConfig();

        return [
            'model' => $config['model'],
            'timeout' => $config['timeout'],
            'temperature' => $config['temperature'],
            'max_output_tokens' => $config['max_output_tokens'],
            'enabled' => $config['enabled'],
            'api_key_masked' => $this->maskKey($config['api_key'] ?? null),
        ];
    }

    protected function maskKey(?string $key): ?string
    {
        if (!$key) {
            return null;
        }

        $length = strlen($key);

        if ($length <= 8) {
            return str_repeat('*', $length);
        }

        return substr($key, 0, 6)
            . str_repeat('*', max(0, $length - 10))
            . substr($key, -4);
    }
}
