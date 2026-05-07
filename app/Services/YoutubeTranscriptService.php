<?php

namespace App\Services;

use MrMySQL\YoutubeTranscript\TranscriptListFetcher;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\HttpFactory;
use App\Models\CourseLecture;
use Exception;
use MrMySQL\YoutubeTranscript\Exception\YoutubeTranscriptExceptionInterface;

class YoutubeTranscriptService
{
    protected TranscriptListFetcher $fetcher;

    public function __construct()
    {
        // Khởi tạo các dependencies chuẩn PSR-18 và PSR-17 theo yêu cầu của thư viện[cite: 12]
        $httpClient = new Client();
        $httpFactory = new HttpFactory();

        $this->fetcher = new TranscriptListFetcher($httpClient, $httpFactory, $httpFactory);
    }

    /**
     * @param CourseLecture $lecture
     * @return array
     * @throws Exception
     */
    public function fetchTranscript(CourseLecture $lecture): array
    {
        $videoId = $this->extractYoutubeId((string) $lecture->url);

        if (!$videoId) {
            throw new Exception("Không thể trích xuất YouTube ID từ URL: {$lecture->url}");
        }

        try {
            // Lấy danh sách phụ đề[cite: 12]
            $transcriptList = $this->fetcher->fetch($videoId);

            // Ưu tiên lấy tiếng Việt, fallback sang tiếng Anh[cite: 12]
            $transcript = $transcriptList->findTranscript(['vi', 'en']);

            // Nếu là tiếng Anh, có thể dịch tự động sang tiếng Việt (Optional)[cite: 12]
            if ($transcript->language_code !== 'vi') {
                $transcript = $transcript->translate('vi');
            }

            // Lấy dữ liệu raw[cite: 12]
            $rawItems = $transcript->fetch();

            // Chuẩn hóa dữ liệu về cùng format với OpenAI/Vosk
            $cleanedText = collect($rawItems)->pluck('text')->implode(' ');

            return [
                'raw_text' => json_encode($rawItems),
                'cleaned_text' => $cleanedText,
                'language' => 'vi',
                'meta' => [
                    'provider' => 'youtube_scraper',
                    'model' => 'mrmysql/youtube-transcript',
                    'segments_count' => count($rawItems)
                ]
            ];
        } catch (YoutubeTranscriptExceptionInterface $e) {
            // Bắt các lỗi cụ thể như PoTokenRequiredException, TooManyRequestsException[cite: 12]
            throw new Exception("Tier 1 Failed: " . $e->getMessage());
        }
    }

    private function extractYoutubeId(string $url): ?string
    {
        preg_match('/(?:https?:\/\/)?(?:www\.)?(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $url, $matches);
        return $matches[1] ?? null;
    }
}
