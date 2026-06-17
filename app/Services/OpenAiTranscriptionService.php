<?php

namespace App\Services;

use App\Models\CourseLecture;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;
use RuntimeException;

class OpenAiTranscriptionService
{
    // hàm xử lý chính
    public function transcribeLecture(CourseLecture $lecture): array
    {
        $this->ensureEnabled();
        $this->ensureLectureIsTranscribable($lecture);

        $workingDir = $this->makeWorkingDirectory();

        try {
            $sourceVideoPath = $this->downloadLectureVideo($lecture, $workingDir);
            $audioPath = $this->extractAudioToMp3($sourceVideoPath, $workingDir);
            $chunkPaths = $this->splitAudioIntoChunks($audioPath, $workingDir);

            // mảng chứa chữ mà openai dịch của từng chunk
            $chunkTranscripts = [];
            $previousTranscriptTail = '';

            foreach ($chunkPaths as $index => $chunkPath) {
                // biến text lưu chữ mà openai dịch của từng chunk
                $text = $this->transcribeChunk(
                    chunkPath: $chunkPath,
                    lecture: $lecture,
                    // giữ lại 1200 kí tự trước đó để không bị ngắt câu
                    previousTranscriptTail: $previousTranscriptTail
                );
                // lưu chữ đã dịch của từng chunk vào mảng
                $chunkTranscripts[] = [
                    'index' => $index,
                    'file_name' => basename($chunkPath),
                    'text' => $text,
                ];
                // lấy 1200 kí tự cuối cùng để nối vào chunk sau tránh bị ngắt câu
                $previousTranscriptTail = $this->tailText(
                    trim($previousTranscriptTail . "\n" . $text),
                    1200
                );
            }

            // nối chữ của các chunk lại
            $rawText = collect($chunkTranscripts)
                ->pluck('text')
                ->filter()
                ->implode("\n\n");

            // xóa bỏ các ký tự không cần thiết
            $cleanedText = $this->cleanTranscript($rawText);

            // đảm bảo văn bản không bị trống không
            if ($cleanedText === '') {
                throw new RuntimeException('Không tạo được transcript hợp lệ từ OpenAI transcription.');
            }

            return [
                'raw_text' => $rawText, // bản thô
                'cleaned_text' => $cleanedText, // bản đã làm sạch
                'segments' => $chunkTranscripts, // mảng chữ đã dịch của từng chunk
                'language' => config('services.openai_transcription.language', 'vi'),
                'meta' => [
                    'provider' => 'openai',
                    'model' => config('services.openai_transcription.model', 'gpt-4o-mini-transcribe'),
                    'chunks_count' => count($chunkPaths),
                ],
            ];
        } finally {
            $this->cleanupDirectory($workingDir);
        }
    }

    // hàm đảm bảo OpenAI transcription được bật
    protected function ensureEnabled(): void
    {
        if (! config('services.openai_transcription.enabled')) {
            throw new RuntimeException('OpenAI transcription đang bị tắt.');
        }

        if (! config('services.openai_transcription.api_key')) {
            throw new RuntimeException('Thiếu OPENAI_API_KEY cho transcription.');
        }
    }

    // hàm đảm bảo lecture hợp lệ để tạo transcript
    protected function ensureLectureIsTranscribable(CourseLecture $lecture): void
    {
        if (! in_array($lecture->type, ['video', 'r2_video'], true)) {
            throw new RuntimeException('Lecture hiện tại không phải video hợp lệ để tạo transcript.');
        }

        if (! $lecture->url) {
            throw new RuntimeException('Lecture chưa có URL video để tạo transcript.');
        }
    }

    // hàm tạo thư mục làm việc cho transcription
    protected function makeWorkingDirectory(): string
    {
        $dir = storage_path('app/tmp/transcription_' . Str::uuid());

        if (! is_dir($dir) && ! mkdir($dir, 0777, true) && ! is_dir($dir)) {
            throw new RuntimeException('Không tạo được thư mục làm việc cho transcription.');
        }

        return $dir;
    }

    // hàm download video từ lecture
    protected function downloadLectureVideo(CourseLecture $lecture, string $workingDir): string
    {
        $sourceUrl = (string) $lecture->url;

        // Nếu là video R2 và URL không bắt đầu bằng http, chúng ta cần ghép domain R2 vào
        if ($lecture->type === 'r2_video' && !Str::startsWith($sourceUrl, ['http://', 'https://'])) {
            $r2Url = rtrim(config('filesystems.disks.r2.url', ''), '/');
            if ($r2Url === '') {
                throw new RuntimeException('Chưa cấu hình CLOUDFLARE_R2_URL trong file .env');
            }
            $sourceUrl = $r2Url . '/' . ltrim($sourceUrl, '/');
        }

        if ($sourceUrl === '' || !Str::startsWith($sourceUrl, ['http://', 'https://'])) {
            throw new RuntimeException('URL video không hợp lệ hoặc không có scheme (http/https): ' . ($sourceUrl ?: 'trống'));
        }

        if ($this->isYoutubeUrl($sourceUrl)) {
            return $this->downloadYoutubeVideo($sourceUrl, $workingDir);
        }

        $extension = $this->guessExtensionFromLecture($lecture);
        $targetPath = $workingDir . DIRECTORY_SEPARATOR . 'source.' . $extension;

        try {
            $response = Http::timeout(300)
                ->withOptions(['stream' => true])
                ->get($sourceUrl);

            if (!$response->successful()) {
                throw new RuntimeException("Không tải được video từ nguồn (Status: {$response->status()}): {$sourceUrl}");
            }

            file_put_contents($targetPath, $response->body());
        } catch (\Exception $e) {
            throw new RuntimeException("Lỗi khi tải video ({$sourceUrl}): " . $e->getMessage());
        }

        if (!file_exists($targetPath) || filesize($targetPath) === 0) {
            throw new RuntimeException('Video tải về rỗng hoặc không tồn tại trên đĩa tạm.');
        }

        return $targetPath;
    }

    protected function isYoutubeUrl(string $url): bool
    {
        return Str::contains($url, ['youtube.com', 'youtu.be']);
    }

    protected function downloadYoutubeVideo(string $sourceUrl, string $workingDir): string
    {
        $targetPath = $workingDir . DIRECTORY_SEPARATOR . 'source.mp4';
        $ytDlp = config('services.yt_dlp.bin', 'yt-dlp');

        $result = Process::timeout((int) config('services.yt_dlp.timeout', 3600))->run([
            $ytDlp,
            '--no-playlist',
            '-f',
            'bv*+ba/best',
            '--merge-output-format',
            'mp4',
            '-o',
            $targetPath,
            $sourceUrl,
        ]);

        if ($result->failed()) {
            throw new RuntimeException('yt-dlp download video loi: ' . trim($result->errorOutput() ?: $result->output()));
        }

        if (! file_exists($targetPath) || filesize($targetPath) === 0) {
            throw new RuntimeException('yt-dlp khong tao duoc file video tam.');
        }

        return $targetPath;
    }

    // hàm đoán extension từ lecture
    protected function guessExtensionFromLecture(CourseLecture $lecture): string
    {
        $fileName = (string) ($lecture->file_name ?? '');
        $mimeType = (string) ($lecture->mime_type ?? '');
        $url = (string) ($lecture->url ?? '');

        $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        if ($extension !== '') {
            return $extension;
        }

        $urlExtension = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        if ($urlExtension !== '') {
            return $urlExtension;
        }

        return match ($mimeType) {
            'audio/mpeg' => 'mp3',
            'audio/mp4', 'video/mp4' => 'mp4',
            'audio/wav' => 'wav',
            'audio/webm', 'video/webm' => 'webm',
            default => 'mp4',
        };
    }

    // hàm extract audio từ video
    protected function extractAudioToMp3(string $sourceVideoPath, string $workingDir): string
    {
        $audioPath = $workingDir . DIRECTORY_SEPARATOR . 'audio.mp3';
        $ffmpeg = config('services.ffmpeg.bin', 'ffmpeg');

        $result = Process::timeout(600)->run([
            $ffmpeg,
            '-y', // ghi đè file cũ nếu có
            '-i', // đường dẫn input file
            $sourceVideoPath,
            '-vn', // không xuất video
            '-ac', // gom thành 1 channel
            '1',
            '-ar', // giảm tần số xuống 16kHz
            '16000',
            '-b:a', // nén bitrate xuống 32kbps (nhỏ, đủ nghe)
            '32k',
            $audioPath, // đường dẫn output file
        ]);

        if ($result->failed()) {
            throw new RuntimeException('ffmpeg extract audio lỗi: ' . $result->errorOutput());
        }

        if (! file_exists($audioPath) || filesize($audioPath) === 0) {
            throw new RuntimeException('Không tạo được file audio mp3.');
        }

        return $audioPath;
    }

    // hàm split audio thành các chunk
    protected function splitAudioIntoChunks(string $audioPath, string $workingDir): array
    {
        $maxApiBytes = 24 * 1024 * 1024; // 24MB OpenAI limit

        // nhỏ hơn 24mb thì không cần split
        if (filesize($audioPath) <= $maxApiBytes) {
            return [$audioPath];
        }

        $chunksDir = $workingDir . DIRECTORY_SEPARATOR . 'chunks';
        if (! is_dir($chunksDir) && ! mkdir($chunksDir, 0777, true) && ! is_dir($chunksDir)) {
            throw new RuntimeException('Không tạo được thư mục chunks.');
        }

        $ffmpeg = config('services.ffmpeg.bin', 'ffmpeg');
        $pattern = $chunksDir . DIRECTORY_SEPARATOR . 'chunk_%03d.mp3';

        // nếu lớn hơn 24mb thì split ra 540s mỗi chunk
        $result = Process::timeout(600)->run([
            $ffmpeg,
            '-y',
            '-i',
            $audioPath,
            '-f',
            'segment', // kích hoạt chế độ cắt file
            '-segment_time',
            '540', // cứ 540s (9p) thì cắt ra thành 1 file mp3 mới
            '-c',
            'copy', // không nén lại nữa mà copy nguyên bản cho nhanh
            $pattern, // output pattern
        ]);

        if ($result->failed()) {
            throw new RuntimeException('ffmpeg split audio lỗi: ' . $result->errorOutput());
        }

        $chunkPaths = glob($chunksDir . DIRECTORY_SEPARATOR . 'chunk_*.mp3') ?: [];
        sort($chunkPaths);

        if (empty($chunkPaths)) {
            throw new RuntimeException('Không tạo được audio chunks.');
        }

        return $chunkPaths;
    }

    // hàm transcribe chunk
    protected function transcribeChunk(
        string $chunkPath,
        CourseLecture $lecture,
        string $previousTranscriptTail = ''
    ): string {
        $apiKey = config('services.openai_transcription.api_key');
        $baseUrl = rtrim(config('services.openai_transcription.base_url', 'https://api.openai.com/v1'), '/');
        $model = config('services.openai_transcription.model', 'gpt-4o-mini-transcribe');
        $timeout = (int) config('services.openai_transcription.timeout', 600);
        $language = config('services.openai_transcription.language', 'vi');

        $prompt = $this->buildPrompt($lecture, $previousTranscriptTail);

        // gửi yêu cầu tới openai
        $response = Http::timeout($timeout)
            ->withToken($apiKey)
            // đính kèm file audio chunk cần transcribe
            ->attach(
                'file',
                fopen($chunkPath, 'r'),
                basename($chunkPath)
            )
            ->post($baseUrl . '/audio/transcriptions', [
                'model' => $model,
                'response_format' => 'json',
                'language' => $language,
                'prompt' => $prompt,
            ]);

        if (! $response->successful()) {
            throw new RuntimeException(
                'OpenAI transcription lỗi: ' . $response->status() . ' - ' . $response->body()
            );
        }

        $data = $response->json();
        $text = trim((string) data_get($data, 'text'));

        if ($text === '') {
            throw new RuntimeException('OpenAI transcription trả về text rỗng.');
        }

        return $text;
    }

    // hàm build prompt để đưa vào OpenAI
    protected function buildPrompt(CourseLecture $lecture, string $previousTranscriptTail = ''): string
    {
        $lectureTitle = trim((string) ($lecture->lecture_title ?? ''));
        $courseTitle = trim((string) optional($lecture->course)->course_title);

        $prompt = "Đây là transcript của một bài giảng e-learning bằng tiếng Việt."
            . " Ưu tiên giữ đúng thuật ngữ kỹ thuật, tên framework, class, method, API, database."
            . " Tên khóa học: {$courseTitle}. Tên bài học: {$lectureTitle}.";

        if ($previousTranscriptTail !== '') {
            $prompt .= " Đây là phần transcript ngay trước đó để giữ ngữ cảnh liên tục: " . $previousTranscriptTail;
        }

        return $prompt;
    }

    protected function cleanTranscript(string $text): string
    {
        // đưa tất cả về định dạng dấu xuống dòng 
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        // thay thế các ký tự tab, space liên tiếp 
        $text = preg_replace('/[ \t]+/u', ' ', $text);
        // nếu có nhiều hơn 2 dấu xuống dòng liên tiếp thì chỉ giữ lại 2 dấu
        $text = preg_replace('/\n{3,}/u', "\n\n", $text);

        return trim((string) $text);
    }

    // hàm cắt đuôi văn bản
    protected function tailText(string $text, int $maxChars): string
    {
        $text = trim($text);

        if (mb_strlen($text) <= $maxChars) {
            return $text;
        }

        return mb_substr($text, -$maxChars);
    }

    // hàm dọn dẹp thư mục
    protected function cleanupDirectory(string $dir): void
    {
        if (! is_dir($dir)) {
            return;
        }

        $items = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \FilesystemIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );

        foreach ($items as $item) {
            $item->isDir() ? @rmdir($item->getPathname()) : @unlink($item->getPathname());
        }

        @rmdir($dir);
    }
}
