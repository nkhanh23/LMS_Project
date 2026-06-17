<?php

namespace App\Services;

use App\Models\CourseLecture;
use Illuminate\Support\Facades\Log;

class TranscriptOrchestratorService
{
    public function __construct(
        protected LocalWhisperTranscriptionService $localWhisperService,
        protected OpenAiTranscriptionService $openAiService
    ) {}

    public function generate(CourseLecture $lecture): array
    {
        if (config('services.transcription_provider', 'openai') === 'local_whisper') {
            Log::info("STT: Kich hoat LocalWhisperTranscriptionService cho Lecture {$lecture->id}");
            return $this->localWhisperService->transcribeLecture($lecture);
        }

        Log::info("STT: Kich hoat OpenAiTranscriptionService cho Lecture {$lecture->id}");
        return $this->openAiService->transcribeLecture($lecture);
    }
}
