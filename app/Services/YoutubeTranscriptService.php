<?php

namespace App\Services;

use App\Models\CourseLecture;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use Illuminate\Support\Facades\Http;
use MrMySQL\YoutubeTranscript\Exception\YoutubeTranscriptExceptionInterface;
use MrMySQL\YoutubeTranscript\TranscriptListFetcher;
use Throwable;

class YoutubeTranscriptService
{
    protected TranscriptListFetcher $fetcher;

    public function __construct()
    {
        $httpClient = new Client();
        $httpFactory = new HttpFactory();

        $this->fetcher = new TranscriptListFetcher($httpClient, $httpFactory, $httpFactory);
    }

    public function fetchTranscript(CourseLecture $lecture): array
    {
        $videoId = $this->extractYoutubeId((string) $lecture->url);

        if (!$videoId) {
            throw new Exception("Không thể trích xuất YouTube ID từ URL: {$lecture->url}");
        }

        $apiUrl = trim((string) config('services.youtube_transcript.api_url'));
        $externalError = null;

        if ($apiUrl !== '') {
            try {
                return $this->fetchFromExternalApi((string) $lecture->url, $apiUrl);
            } catch (Throwable $e) {
                $externalError = $e;

                if (!config('services.youtube_transcript.fallback_enabled', true)) {
                    throw new Exception('Transcript API failed: ' . $e->getMessage(), previous: $e);
                }
            }
        }

        try {
            $transcriptList = $this->fetcher->fetch($videoId);
            $transcript = $transcriptList->findTranscript(['vi', 'en']);

            if ($transcript->language_code !== 'vi') {
                $transcript = $transcript->translate('vi');
            }

            $rawItems = $transcript->fetch();
            $cleanedText = collect($rawItems)->pluck('text')->implode(' ');

            return [
                'raw_text' => json_encode($rawItems, JSON_UNESCAPED_UNICODE),
                'cleaned_text' => $cleanedText,
                'language' => 'vi',
                'meta' => [
                    'provider' => 'youtube_scraper',
                    'model' => 'mrmysql/youtube-transcript',
                    'segments_count' => count($rawItems),
                ],
            ];
        } catch (YoutubeTranscriptExceptionInterface $e) {
            $message = 'Tier 1 Failed: ' . $e->getMessage();

            if ($externalError) {
                $message = 'Transcript API failed: ' . $externalError->getMessage() . '; ' . $message;
            }

            throw new Exception($message, previous: $e);
        }
    }

    private function fetchFromExternalApi(string $videoUrl, string $apiUrl): array
    {
        $response = Http::timeout((int) config('services.youtube_transcript.timeout', 45))
            ->acceptJson()
            ->post($apiUrl, [
                'video_url' => $videoUrl,
            ]);

        if (!$response->successful()) {
            $message = $response->json('detail')
                ?? $response->json('message')
                ?? $response->body()
                ?: 'External transcript API returned HTTP ' . $response->status();

            throw new Exception((string) $message);
        }

        $payload = $response->json();
        $transcript = $payload['transcript'] ?? null;

        if (is_array($transcript)) {
            $transcript = collect($transcript)
                ->map(fn ($item) => is_array($item) ? ($item['text'] ?? '') : (string) $item)
                ->filter()
                ->implode(' ');
        }

        $cleanedText = trim((string) $transcript);

        if ($cleanedText === '') {
            throw new Exception('External transcript API returned empty transcript.');
        }

        return [
            'raw_text' => json_encode($payload, JSON_UNESCAPED_UNICODE),
            'cleaned_text' => $cleanedText,
            'language' => $payload['language'] ?? config('services.openai_transcription.language', 'vi'),
            'meta' => [
                'provider' => 'external_youtube_transcript_api',
                'model' => 'jaypaun007/youtube-transcript-api',
                'api_url' => $apiUrl,
                'segments_count' => is_array($payload['segments'] ?? null) ? count($payload['segments']) : null,
            ],
        ];
    }

    private function extractYoutubeId(string $url): ?string
    {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);

        return $matches[1] ?? null;
    }
}
