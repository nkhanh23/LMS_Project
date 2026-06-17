<?php

namespace App\Services;

use App\Models\CourseGoal;
use App\Models\User;
use App\Notifications\NewLecturePublishedNotification;
use App\Repositories\LectureRepository;
use App\Traits\FileUploadTrait;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class LectureService
{
    use FileUploadTrait;
    protected $lectureRepository;
    public function __construct(LectureRepository $lectureRepository)
    {
        $this->lectureRepository = $lectureRepository;
    }

    public function createLecture(array $data)
    {
        // Gọi hàm xử lý Business Logic để chuẩn bị dữ liệu
        $preparedData = $this->prepareLectureData($data);

        $lecture = $this->lectureRepository->createLecture($preparedData);

        // Gửi thông báo bài học mới cho học viên đã đăng ký khóa học (nếu khóa học đã xuất bản)
        $this->notifyEnrolledStudents($lecture);

        return $lecture;
    }

    /**
     * Gửi thông báo bài học mới cho tất cả học viên đã đăng ký khóa học.
     */
    protected function notifyEnrolledStudents($lecture): void
    {
        try {
            $course = \App\Models\Course::find($lecture->course_id);

            if (!$course || !$course->isPublished()) {
                return;
            }

            $students = $course->enrollments()
                ->where('status', 'active')
                ->with('user')
                ->get()
                ->pluck('user')
                ->filter(function ($user) {
                    return $user && $user->role === 'user';
                });

            if ($students->isEmpty()) {
                return;
            }

            Notification::send(
                $students,
                new NewLecturePublishedNotification($lecture, $course)
            );
        } catch (\Exception $e) {
            Log::error('Lỗi gửi thông báo bài học mới: ' . $e->getMessage());
        }
    }

    /**
     * Logic tạo Presigned URL của R2
     */
    public function generatePresignedUrl(string $extension): array
    {
        //tạo tên file ngẫu nhiên (UUID) để tránh trùng lặp trên R2
        $filename = 'course_videos/' . Str::uuid() . '.' . $extension;

        //tạo S3 Client trực tiếp từ cấu hình r2
        $client = new S3Client([
            'region'  => config('filesystems.disks.r2.region', 'auto'),
            'version' => 'latest',
            'endpoint' => config('filesystems.disks.r2.endpoint'),
            'use_path_style_endpoint' => config('filesystems.disks.r2.use_path_style_endpoint', true),
            'credentials' => [
                'key'    => config('filesystems.disks.r2.key'),
                'secret' => config('filesystems.disks.r2.secret'),
            ],
        ]);

        //tạo command dặn r2 rằng: sắp có người tải một vật thể lên.
        //vật thể đó sẽ nằm ở cái bucket này, mang tên là key này, và có định dạng video.
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.r2.bucket'),
            'Key'    => $filename,
            'ContentType' => 'video/' . $extension,
        ]);

        //tạo url sống trong 30 phút
        $presignedRequest = $client->createPresignedRequest($command, '+30 minutes');

        //trả về mảng dữ liệu cho controller
        return [
            'upload_url' => (string) $presignedRequest->getUri(),
            'file_key'   => $filename
        ];
    }

    /**
     * Chuẩn bị dữ liệu trước khi lưu DB
     */
    public function prepareLectureData(array $data)
    {
        switch ($data['type']) {
            case 'document':
                if (!empty($data['r2_document_key'])) {
                    $data['url'] = $data['r2_document_key'];
                    $data['storage_disk'] = 'r2';
                } else {
                    $data['url'] = null;
                    $data['storage_disk'] = null;
                }

                $data['video_duration'] = null;
                break;

            case 'text':
                $data['url'] = null;
                $data['video_duration'] = null;
                $data['storage_disk'] = null;
                $data['file_name'] = null;
                $data['mime_type'] = null;
                $data['file_size'] = null;
                break;

            case 'r2_video':
                // FRONTEND ĐÃ UP FILE XONG VÀ GỬI KEY LÊN ĐÂY
                if (isset($data['r2_video_key'])) {
                    // Chỉ việc lưu cái Key (tên file) vào cột URL
                    $data['url'] = $data['r2_video_key'];
                }
                break;

            case 'video':
                // Link youtube mặc định
                break;

            case 'quiz':
                $data['url'] = null;
                $data['content'] = null;
                $data['video_duration'] = null;
                break;
        }

        // Xóa các dữ liệu rác để khỏi lỗi Eloquent Missing Column
        unset($data['document_file'], $data['video_file'], $data['r2_video_key'], $data['r2_document_key']);

        return $data;
    }

    /**
     * 
     */
    public function updateLecture(array $data, $id)
    {
        $lecture = $this->lectureRepository->getLectureById($id);

        if ($data['type'] === 'document') {
            if (!empty($data['r2_document_key'])) {
                $data['url'] = $data['r2_document_key'];
                $data['storage_disk'] = 'r2';
            } else {
                $data['url'] = $lecture->type === 'document' ? $lecture->url : null;
                $data['storage_disk'] = $lecture->type === 'document' ? $lecture->storage_disk : null;
                $data['file_name'] = $lecture->type === 'document' ? $lecture->file_name : null;
                $data['mime_type'] = $lecture->type === 'document' ? $lecture->mime_type : null;
                $data['file_size'] = $lecture->type === 'document' ? $lecture->file_size : null;
            }

            $data['video_duration'] = null;
        } elseif ($data['type'] === 'r2_video') {
            if (!empty($data['r2_video_key'])) {
                $data['url'] = $data['r2_video_key'];
            } else {
                // Giữ nguyên URL cũ nếu không upload video mới
                $data['url'] = $lecture->type === 'r2_video' ? $lecture->url : null;
            }
            $data['video_duration'] = null;
        }

        // Xóa file cũ trên R2 nếu có upload file mới
        if ($data['type'] === 'document' && !empty($data['r2_document_key'])) {
            if ($lecture->storage_disk === 'r2' && !empty($lecture->url)) {
                $this->deleteFileFromR2($lecture->url);
            }
        } elseif ($data['type'] === 'r2_video' && !empty($data['r2_video_key'])) {
            if ($lecture->type === 'r2_video' && !empty($lecture->url)) {
                $this->deleteFileFromR2($lecture->url);
            }
        }

        // Xóa các dữ liệu rác trước khi lưu vào DB
        unset($data['document_file'], $data['video_file'], $data['r2_video_key'], $data['r2_document_key']);

        return $this->lectureRepository->updateLecture($data, $id);
    }

    /**
     * 
     */
    public function generateDocumentPresignedUrl(string $extension, string $mimeType): array
    {
        // Tạo tên file duy nhất trên R2 (ví dụ: course_documents/123e4567-e89b-12d3-a456-426614174000.pdf)
        $filename = 'course_documents/' . Str::uuid() . '.' . $extension;

        $client = new S3Client([
            'region'  => config('filesystems.disks.r2.region', 'auto'),
            'version' => 'latest',
            'endpoint' => config('filesystems.disks.r2.endpoint'),
            'use_path_style_endpoint' => config('filesystems.disks.r2.use_path_style_endpoint', true),
            'credentials' => [
                'key'    => config('filesystems.disks.r2.key'),
                'secret' => config('filesystems.disks.r2.secret'),
            ],
        ]);

        // Tạo một "Mệnh lệnh" (Command) dặn R2 rằng: Sắp có người tải một vật thể (PutObject) lên.
        // Vật thể đó sẽ nằm ở cái 'Bucket' này, mang tên là 'Key' này, và có định dạng video.
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.r2.bucket'),
            'Key'    => $filename,
            'ContentType' => $mimeType,
        ]);

        $presignedRequest = $client->createPresignedRequest($command, '+30 minutes');

        return [
            'upload_url' => (string) $presignedRequest->getUri(),
            'file_key'   => $filename,
        ];
    }

    public function deleteLecture(int $id)
    {
        $lecture = $this->lectureRepository->getLectureById($id);

        // Xóa file trên R2 nếu có
        if ($lecture->storage_disk === 'r2' || $lecture->type === 'r2_video') {
            if (!empty($lecture->url)) {
                $this->deleteFileFromR2($lecture->url);
            }
        }

        return $this->lectureRepository->deleteLecture($id);
    }

    public function deleteFileFromR2(string $key)
    {
        $client = new S3Client([
            'region'  => config('filesystems.disks.r2.region', 'auto'),
            'version' => 'latest',
            'endpoint' => config('filesystems.disks.r2.endpoint'),
            'use_path_style_endpoint' => config('filesystems.disks.r2.use_path_style_endpoint', true),
            'credentials' => [
                'key'    => config('filesystems.disks.r2.key'),
                'secret' => config('filesystems.disks.r2.secret'),
            ],
        ]);

        try {
            $client->deleteObject([
                'Bucket' => config('filesystems.disks.r2.bucket'),
                'Key'    => $key,
            ]);
            return true;
        } catch (\Exception $e) {
            Log::error("Lỗi khi xóa file trên R2 (Key: $key): " . $e->getMessage());
            return false;
        }
    }

    public function generateDocumentDownloadUrl(string $key, ?string $downloadName = null): string
    {
        $client = new S3Client([
            'region'  => config('filesystems.disks.r2.region', 'auto'),
            'version' => 'latest',
            'endpoint' => config('filesystems.disks.r2.endpoint'),
            'use_path_style_endpoint' => config('filesystems.disks.r2.use_path_style_endpoint', true),
            'credentials' => [
                'key'    => config('filesystems.disks.r2.key'),
                'secret' => config('filesystems.disks.r2.secret'),
            ],
        ]);

        $params = [
            'Bucket' => config('filesystems.disks.r2.bucket'),
            'Key'    => $key,
        ];

        if (!empty($downloadName)) {
            // Bỏ các dấu nháy kép, dấu xuống dòng...
            $safeName = str_replace(['"', "\r", "\n"], '', $downloadName);

            // 'attachment' ép trình duyệt phải TẢI XUỐNG thay vì mở xem trực tiếp trên tab mới.
            // 'filename="..."' ép trình duyệt lưu file vào máy người dùng bằng tên $safeName thay vì tên chuỗi UUID xấu xí trên R2.
            $params['ResponseContentDisposition'] = 'attachment; filename="' . $safeName . '"';
        }

        // Tạo một "Mệnh lệnh" (Command) dặn R2 rằng: Sắp có người tải một vật thể (GetObject) xuống.
        $command = $client->getCommand('GetObject', $params);

        // Tạo URL tải xuống tạm thời (có hiệu lực trong 10 phút)
        $presignedRequest = $client->createPresignedRequest($command, '+10 minutes');

        return (string) $presignedRequest->getUri();
    }
}
