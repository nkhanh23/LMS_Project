<?php

namespace App\Repositories;

use App\Models\Slider;
use App\Traits\FileUploadTrait;

class SliderRepository
{
    use FileUploadTrait;

    public function saveSlider($data, $image)
    {
        $slider = new Slider();

        //Xử lý upload file
        if ($image) {
            $data['image'] = $this->uploadFile($image, 'slider', $slider->image);
        }
        $slider->create($data);
        return $slider;
    }

    public function updateSlider($data, $image, $id)
    {
        $slider = Slider::find($id);
        if ($image) {
            $data['image'] = $this->uploadFile($image, 'slider', $slider->image);
        }
        $slider->update($data);
        return $slider;
    }
}
