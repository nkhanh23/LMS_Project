<?php

namespace App\Services;

use App\Jobs\GenerateTranscriptJob;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Models\TranscriptJob;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use InvalidArgumentException;
use RuntimeException;

class YoutubePlaylistImportService
{
    public function import(array $data, int $instructorId): Course
    {
        $playlistId = $this->extractPlaylistId($data['playlist_url']);
        $playlist = $this->fetchPlaylist($playlistId, $data['youtube_api_key']);
        $videos = $this->fetchPlaylistVideos(
            $playlistId,
            $data['youtube_api_key'],
            (int) ($data['max_videos'] ?? 100)
        );

        if (empty($videos)) {
            throw new RuntimeException('Danh sách phát không có video hợp lệ để import.');
        }

        return DB::transaction(function () use ($data, $instructorId, $playlist, $videos) {
            $courseName = trim((string) ($data['course_name'] ?? '')) ?: $playlist['title'];
            $courseTitle = trim((string) ($data['course_title'] ?? '')) ?: $courseName;
            $firstVideoUrl = 'https://www.youtube.com/watch?v=' . $videos[0]['video_id'];

            $course = Course::create([
                'category_id' => $data['category_id'],
                'subcategory_id' => $data['subcategory_id'],
                'instructor_id' => $instructorId,
                'course_title' => $courseTitle,
                'course_name' => $courseName,
                'course_name_slug' => $this->uniqueCourseSlug($courseName),
                'course_image' => $playlist['thumbnail_url'] ?? null,
                'description' => trim((string) ($data['description'] ?? '')) ?: ($playlist['description'] ?: $courseTitle),
                'video_url' => $firstVideoUrl,
                'label' => $data['label'] ?? null,
                'duration' => null,
                'resources' => count($videos),
                'certificate' => $data['certificate'] ?? 'no',
                'selling_price' => $data['selling_price'],
                'discount_price' => $data['discount_price'] ?? null,
                'prerequisites' => null,
                'bestseller' => 'no',
                'featured' => 'no',
                'highestrated' => 'no',
                'status' => 0,
                'approval_status' => 'draft',
            ]);

            $section = CourseSection::create([
                'course_id' => $course->id,
                'section_title' => trim((string) ($data['section_title'] ?? '')) ?: 'YouTube Playlist',
                'sort_order' => 1,
            ]);

            $transcriptJobs = [];

            foreach ($videos as $index => $video) {
                $lecture = CourseLecture::create([
                    'course_id' => $course->id,
                    'section_id' => $section->id,
                    'lecture_title' => $video['title'],
                    'sort_order' => $index + 1,
                    'is_preview' => $index === 0,
                    'type' => 'video',
                    'url' => 'https://www.youtube.com/watch?v=' . $video['video_id'],
                    'content' => $video['description'] ?: null,
                ]);

                $transcriptJob = TranscriptJob::query()->create([
                    'lecture_id' => $lecture->id,
                    'course_id' => $course->id,
                    'requested_by' => $instructorId,
                    'status' => 'queued',
                    'progress' => 0,
                    'request_payload' => [
                        'source' => 'youtube_playlist_import',
                        'video_id' => $video['video_id'],
                        'video_url' => $lecture->url,
                    ],
                ]);

                $transcriptJobs[] = [
                    'id' => $transcriptJob->id,
                    'delay_seconds' => $index * (int) config('services.transcript.dispatch_delay_seconds', 30),
                ];
            }

            DB::afterCommit(function () use ($transcriptJobs) {
                foreach ($transcriptJobs as $transcriptJob) {
                    GenerateTranscriptJob::dispatch($transcriptJob['id'])
                        ->onConnection(config('services.transcript.queue_connection', 'database'))
                        ->onQueue(config('services.transcript.queue', 'transcripts'))
                        ->delay(now()->addSeconds($transcriptJob['delay_seconds']));
                }
            });

            return $course->load('sections.lecture');
        });
    }

    public function extractPlaylistId(string $url): string
    {
        $query = parse_url($url, PHP_URL_QUERY);
        parse_str((string) $query, $params);

        $playlistId = trim((string) ($params['list'] ?? ''));

        if ($playlistId === '') {
            throw new InvalidArgumentException('Không tìm thấy playlist id trong URL YouTube.');
        }

        return $playlistId;
    }

    private function fetchPlaylist(string $playlistId, string $apiKey): array
    {
        $response = Http::timeout(20)->get('https://www.googleapis.com/youtube/v3/playlists', [
            'part' => 'snippet',
            'id' => $playlistId,
            'key' => $apiKey,
            'maxResults' => 1,
        ]);

        $this->throwForYoutubeError($response->json(), $response->status());

        $item = $response->json('items.0');
        if (!$item) {
            throw new RuntimeException('Không tìm thấy danh sách phát YouTube hoặc API key không có quyền truy cập.');
        }

        return [
            'title' => $item['snippet']['title'] ?? 'Imported YouTube Course',
            'description' => $item['snippet']['description'] ?? '',
            'thumbnail_url' => $this->bestThumbnailUrl($item['snippet']['thumbnails'] ?? []),
        ];
    }

    private function fetchPlaylistVideos(string $playlistId, string $apiKey, int $maxVideos): array
    {
        $videos = [];
        $pageToken = null;

        do {
            $response = Http::timeout(20)->get('https://www.googleapis.com/youtube/v3/playlistItems', [
                'part' => 'snippet,contentDetails',
                'playlistId' => $playlistId,
                'key' => $apiKey,
                'maxResults' => min(50, $maxVideos - count($videos)),
                'pageToken' => $pageToken,
            ]);

            $payload = $response->json();
            $this->throwForYoutubeError($payload, $response->status());

            foreach ($payload['items'] ?? [] as $item) {
                $videoId = $item['contentDetails']['videoId'] ?? null;
                $title = $item['snippet']['title'] ?? null;

                if (!$videoId || !$title || in_array($title, ['Private video', 'Deleted video'], true)) {
                    continue;
                }

                $videos[] = [
                    'video_id' => $videoId,
                    'title' => Str::limit($title, 255, ''),
                    'description' => $item['snippet']['description'] ?? '',
                ];

                if (count($videos) >= $maxVideos) {
                    break 2;
                }
            }

            $pageToken = $payload['nextPageToken'] ?? null;
        } while ($pageToken);

        return $videos;
    }

    private function throwForYoutubeError(?array $payload, int $status): void
    {
        if ($status >= 200 && $status < 300) {
            return;
        }

        $message = $payload['error']['message'] ?? 'Không thể gọi YouTube Data API.';
        throw new RuntimeException($message);
    }

    private function bestThumbnailUrl(array $thumbnails): ?string
    {
        foreach (['maxres', 'standard', 'high', 'medium', 'default'] as $quality) {
            if (!empty($thumbnails[$quality]['url'])) {
                return $thumbnails[$quality]['url'];
            }
        }

        return null;
    }

    private function uniqueCourseSlug(string $courseName): string
    {
        $base = Str::slug($courseName) ?: 'youtube-course';
        $slug = $base;
        $counter = 2;

        while (Course::where('course_name_slug', $slug)->exists()) {
            $slug = $base . '-' . $counter;
            $counter++;
        }

        return $slug;
    }
}
