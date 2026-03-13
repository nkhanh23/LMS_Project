<?php

namespace App\Services;

use App\Models\CourseGoal;
use App\Models\User;
use App\Repositories\LectureRepository;
use App\Traits\FileUploadTrait;
use Aws\S3\S3Client;
use Illuminate\Support\Facades\Log;
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

        return $this->lectureRepository->createLecture($preparedData);
    }

    /**
     * Logic tạo Presigned URL của R2
     */
    public function generatePresignedUrl(string $extension): array
    {
        $filename = 'course_videos/' . Str::uuid() . '.' . $extension;

        // Tạo S3 Client trực tiếp từ cấu hình r2
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

        // Yêu cầu quyền Upload (PutObject)
        $command = $client->getCommand('PutObject', [
            'Bucket' => config('filesystems.disks.r2.bucket'),
            'Key'    => $filename,
            'ContentType' => 'video/' . $extension,
        ]);

        // Tạo URL sống trong 30 phút
        $presignedRequest = $client->createPresignedRequest($command, '+30 minutes');

        // Trả về mảng dữ liệu cho Controller
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
            $safeName = str_replace(['"', "\r", "\n"], '', $downloadName);

            $params['ResponseContentDisposition'] = 'attachment; filename="' . $safeName . '"';
        }

        $command = $client->getCommand('GetObject', $params);

        $presignedRequest = $client->createPresignedRequest($command, '+10 minutes');

        return (string) $presignedRequest->getUri();
    }
}
