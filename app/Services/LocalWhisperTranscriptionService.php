<?php

namespace App\Services;

use App\Models\CourseLecture;
use Illuminate\Support\Facades\Process;
use RuntimeException;

class LocalWhisperTranscriptionService extends OpenAiTranscriptionService
{
    public function transcribeLecture(CourseLecture $lecture): array
    {
        $this->ensureEnabled();
        $this->ensureLectureIsTranscribable($lecture);

        $workingDir = $this->makeWorkingDirectory();

        try {
            $sourceVideoPath = $this->downloadLectureVideo($lecture, $workingDir);
            $audioPath = $this->extractAudioToMp3($sourceVideoPath, $workingDir);
            $chunkPaths = $this->splitAudioIntoChunks($audioPath, $workingDir);

            $chunkTranscripts = [];
            $previousTranscriptTail = '';

            foreach ($chunkPaths as $index => $chunkPath) {
                $text = $this->transcribeChunk(
                    chunkPath: $chunkPath,
                    lecture: $lecture,
                    previousTranscriptTail: $previousTranscriptTail,
                    outputDir: $workingDir . DIRECTORY_SEPARATOR . 'whisper_output_' . $index
                );

                $chunkTranscripts[] = [
                    'index' => $index,
                    'file_name' => basename($chunkPath),
                    'text' => $text,
                ];

                $previousTranscriptTail = $this->tailText(
                    trim($previousTranscriptTail . "\n" . $text),
                    1200
                );
            }

            $rawText = collect($chunkTranscripts)
                ->pluck('text')
                ->filter()
                ->implode("\n\n");

            $cleanedText = $this->cleanTranscript($rawText);

            if ($cleanedText === '') {
                throw new RuntimeException('Local Whisper did not return a valid transcript.');
            }

            return [
                'raw_text' => $rawText,
                'cleaned_text' => $cleanedText,
                'segments' => $chunkTranscripts,
                'language' => config('services.local_whisper.language', 'Vietnamese'),
                'meta' => [
                    'provider' => 'local_whisper',
                    'model' => config('services.local_whisper.model', 'base'),
                    'chunks_count' => count($chunkPaths),
                ],
            ];
        } finally {
            $this->cleanupDirectory($workingDir);
        }
    }

    protected function ensureEnabled(): void
    {
        if (! config('services.local_whisper.enabled')) {
            throw new RuntimeException('Local Whisper transcription is disabled.');
        }

        if (! config('services.local_whisper.bin')) {
            throw new RuntimeException('Missing LOCAL_WHISPER_BIN for local transcription.');
        }
    }

    protected function transcribeChunk(
        string $chunkPath,
        CourseLecture $lecture,
        string $previousTranscriptTail = '',
        ?string $outputDir = null
    ): string {
        $outputDir ??= dirname($chunkPath);

        if (! is_dir($outputDir) && ! mkdir($outputDir, 0777, true) && ! is_dir($outputDir)) {
            throw new RuntimeException('Could not create local Whisper output directory.');
        }

        $command = [
            config('services.local_whisper.bin'),
            $chunkPath,
            '--model',
            config('services.local_whisper.model', 'base'),
            '--language',
            config('services.local_whisper.language', 'Vietnamese'),
            '--task',
            'transcribe',
            '--output_format',
            'txt',
            '--output_dir',
            $outputDir,
        ];

        $modelDir = trim((string) config('services.local_whisper.model_dir', ''));
        if ($modelDir !== '') {
            $command[] = '--model_dir';
            $command[] = $modelDir;
        }

        $device = trim((string) config('services.local_whisper.device', ''));
        if ($device !== '' && $device !== 'auto') {
            $command[] = '--device';
            $command[] = $device;
        }

        $prompt = trim($this->buildPrompt($lecture, $previousTranscriptTail));
        if ($prompt !== '') {
            $command[] = '--initial_prompt';
            $command[] = $prompt;
        }

        $result = Process::env([
            'PYTHONIOENCODING' => 'utf-8',
            'PYTHONUTF8' => '1',
        ])
            ->timeout((int) config('services.local_whisper.timeout', 3600))
            ->run($command);

        if ($result->failed()) {
            throw new RuntimeException('Local Whisper failed: ' . trim($result->errorOutput() ?: $result->output()));
        }

        $textPath = $outputDir . DIRECTORY_SEPARATOR . pathinfo($chunkPath, PATHINFO_FILENAME) . '.txt';

        if (! file_exists($textPath)) {
            throw new RuntimeException('Local Whisper did not create a transcript text file.');
        }

        $text = trim((string) file_get_contents($textPath));

        if ($text === '') {
            throw new RuntimeException('Local Whisper returned empty transcript text.');
        }

        return $text;
    }
}
