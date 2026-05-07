<?php

namespace App\Services;

use App\Models\CourseLecture;
use Illuminate\Support\Facades\Log;
use Exception;

class TranscriptOrchestratorService
{
    public function __construct(
        protected YoutubeTranscriptService $youtubeService,
        protected OpenAiTranscriptionService $openAiService
        // protected VoskTranscriptionService $voskService -> Tiêm thêm Tier 3 vào đây sau này
    ) {}

    public function generate(CourseLecture $lecture): array
    {
        // Kiểm tra logic nếu không phải là youtube url thì skip Tier 1
        $isYoutube = str_contains($lecture->url, 'youtube.com') || str_contains($lecture->url, 'youtu.be');

        if ($isYoutube) {
            // ==========================================
            // YouTube: Chỉ dùng YoutubeTranscriptService, thất bại thì báo lỗi
            // ==========================================
            Log::info("STT: Kích hoạt YoutubeTranscriptService cho Lecture {$lecture->id}");
            return $this->youtubeService->fetchTranscript($lecture);
        }

        // ==========================================
        // Không phải YouTube: Dùng OpenAI Whisper API
        // ==========================================
        Log::info("STT: Kích hoạt OpenAiTranscriptionService cho Lecture {$lecture->id}");
        return $this->openAiService->transcribeLecture($lecture);
    }
}