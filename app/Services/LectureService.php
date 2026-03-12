<?php

namespace App\Services;

use App\Models\CourseGoal;
use App\Models\User;
use App\Repositories\LectureRepository;
use App\Traits\FileUploadTrait;
use Aws\S3\S3Client;
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
                if (isset($data['document_file'])) {
                    $data['url'] = $this->uploadFile($data['document_file'], 'lectures');
                } else {
                    $data['url'] = null;
                }
                $data['video_duration'] = null;
                break;

            case 'text':
                $data['url'] = null;
                $data['video_duration'] = null;
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
        unset($data['document_file'], $data['video_file'], $data['r2_video_key']);

        return $data;
    }

    public function updateLecture(array $data, $id)
    {
        $lecture = $this->lectureRepository->getLectureById($id);

        if ($data['type'] === 'document') {
            if (isset($data['document_file'])) {
                $data['url'] = $this->uploadFile($data['document_file'], 'lectures', $lecture->url);
            } else {
                $data['url'] = $lecture->type === 'document' ? $lecture->url : null;
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
        } elseif ($data['type'] === 'text') {
            $data['url'] = null;
            $data['video_duration'] = null;
        }

        // Xóa các dữ liệu rác trước khi lưu vào DB
        unset($data['document_file'], $data['video_file'], $data['r2_video_key']);

        return $this->lectureRepository->updateLecture($data, $id);
    }
}
