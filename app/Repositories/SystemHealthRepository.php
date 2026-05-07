<?php

namespace App\Repositories;

use App\Models\AiChatMessage;
use App\Models\AiDocument;
use App\Models\TranscriptJob;
use Illuminate\Support\Facades\DB;

class SystemHealthRepository
{
    public function getQueueStats(): array
    {
        return [
            'pending_jobs' => DB::table('jobs')->count(),
            'failed_jobs'  => DB::table('failed_jobs')->count(),
            'recent_failed' => DB::table('failed_jobs')->orderBy('failed_at', 'desc')->limit(5)->get(),
        ];
    }

    public function getBackgroundProcessStats(): array
    {
        return [
            'transcript' => [
                'processing' => TranscriptJob::where('status', 'processing')->count(),
                'queued'     => TranscriptJob::where('status', 'queued')->count(),
                'failed'     => TranscriptJob::where('status', 'failed')->count(),
            ],
            'ai_document' => [
                'processing' => AiDocument::where('index_status', 'processing')->count(),
                'pending'    => AiDocument::where('index_status', 'pending')->count(),
                'failed'     => AiDocument::where('index_status', 'failed')->count(),
            ]
        ];
    }

    public function getApiTokenUsage(): array
    {
        // Tính tổng token đã sử dụng trong tháng hiện tại
        $startOfMonth = now()->startOfMonth();

        $geminiUsage = AiChatMessage::where('provider', 'gemini')
            ->where('created_at', '>=', $startOfMonth)
            ->selectRaw('SUM(prompt_tokens) as prompt, SUM(completion_tokens) as completion')
            ->first();

        $openAiUsage = AiChatMessage::where('provider', 'openai')
            ->where('created_at', '>=', $startOfMonth)
            ->selectRaw('SUM(prompt_tokens) as prompt, SUM(completion_tokens) as completion')
            ->first();

        return [
            'gemini' => ($geminiUsage->prompt ?? 0) + ($geminiUsage->completion ?? 0),
            'openai' => ($openAiUsage->prompt ?? 0) + ($openAiUsage->completion ?? 0),
            // Giả định bảng transcript_jobs có lưu số phút đã xử lý để tính Whisper Quota
            'whisper_minutes' => TranscriptJob::where('status', 'done')
                ->where('created_at', '>=', $startOfMonth)
                ->count() * 15, // Giả sử trung bình 1 video 15 phút
        ];
    }
}
