<?php


namespace App\Repositories;

use App\Models\Category;
use App\Models\SiteInfo;
use App\Traits\FileUploadTrait;

class SiteRepository
{
    use FileUploadTrait;



    public function saveSiteService($data, $logo, $favicon)
    {
        $site_info = SiteInfo::find(1);

        if ($logo) {
            $data['logo'] = $this->uploadFile($logo, 'site-info', $site_info->logo ?? null);
        }

        if ($favicon) {
            $data['favicon'] = $this->uploadFile($favicon, 'site-info', $site_info->favicon ?? null);
        }

        // Tạo mới hoặc cập nhật 
        $site_info = SiteInfo::updateOrCreate(
            ['id' => 1],
            $data
        );

        return $site_info;
    }
}
