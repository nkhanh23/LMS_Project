<?php

namespace App\Services;

use App\Repositories\SystemHealthRepository;

class SystemHealthService
{
    protected $repository;

    public function __construct(SystemHealthRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getDashboardData(): array
    {
        $queueStats = $this->repository->getQueueStats();
        $processStats = $this->repository->getBackgroundProcessStats();
        $usageStats = $this->repository->getApiTokenUsage();

        // Cấu hình Quota giả định/thực tế cho mỗi tháng (có thể đưa vào .env)
        $quotas = [
            'gemini'  => env('GEMINI_MONTHLY_TOKEN_QUOTA', 10000000), // 10 triệu tokens
            'openai'  => env('OPENAI_MONTHLY_TOKEN_QUOTA', 5000000),  // 5 triệu tokens
            'whisper' => env('WHISPER_MONTHLY_MINUTE_QUOTA', 1000),   // 1000 phút
        ];

        return [
            'queue'   => $queueStats,
            'process' => $processStats,
            'api_usage' => [
                'gemini' => [
                    'used'    => $usageStats['gemini'],
                    'limit'   => $quotas['gemini'],
                    'percent' => $quotas['gemini'] > 0 ? min(100, round(($usageStats['gemini'] / $quotas['gemini']) * 100, 2)) : 0,
                ],
                'openai' => [
                    'used'    => $usageStats['openai'],
                    'limit'   => $quotas['openai'],
                    'percent' => $quotas['openai'] > 0 ? min(100, round(($usageStats['openai'] / $quotas['openai']) * 100, 2)) : 0,
                ],
                'whisper' => [
                    'used'    => $usageStats['whisper_minutes'],
                    'limit'   => $quotas['whisper'],
                    'percent' => $quotas['whisper'] > 0 ? min(100, round(($usageStats['whisper_minutes'] / $quotas['whisper']) * 100, 2)) : 0,
                ]
            ]
        ];
    }
}
