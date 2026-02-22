<?php

namespace App\Traits;

trait FileUploadTrait
{
    public function uploadFile($file, $folder, $existingFile = null)
    {
        if ($file) {
            //thư mục đích
            $targetFolder = public_path("upload/{$folder}");
            //đảm bảo thư mục đó tồn tại

            if (!file_exists($targetFolder)) {
                mkdir($targetFolder, 0755, true);
            }
            //xóa file nếu tồn tại
            if ($existingFile && file_exists(public_path(parse_url($existingFile, PHP_URL_PATH)))) {
                unlink(public_path(parse_url($existingFile, PHP_URL_PATH)));
            }
            //tạo ra file duy nhất
            $fileName = uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($targetFolder, $fileName);
            return url("upload/{$folder}/{$fileName}");
        }
        return $existingFile;
    }
}
