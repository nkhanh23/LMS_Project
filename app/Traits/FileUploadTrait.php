<?php

namespace App\Traits;

trait FileUploadTrait
{
    public function uploadFile($file, $folder, $existingFile = null)
    {
        if ($file && $file instanceof \Illuminate\Http\UploadedFile) {
            //thư mục đích
            $targetFolder = public_path("upload/{$folder}");
            //đảm bảo thư mục đó tồn tại

            if (!file_exists($targetFolder)) {
                mkdir($targetFolder, 0755, true);
            }
            //xóa file nếu tồn tại
            if (!empty($existingFile) && is_string($existingFile)) {
                $path = parse_url($existingFile, PHP_URL_PATH);
                if ($path) {
                    $oldPath = public_path($path);
                    if (file_exists($oldPath) && !is_dir($oldPath)) {
                        @unlink($oldPath);
                    }
                }
            }
            //tạo ra file duy nhất
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($targetFolder, $fileName);
            return url("upload/{$folder}/{$fileName}");
        }
        return $existingFile;
    }
}
