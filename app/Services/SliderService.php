<?php

namespace App\\Services;

use App\Models\User;
use App\Repositories\SliderRepository;

class SliderService
{
    protected $sliderRepository;
    public function __construct(SliderRepository $sliderRepository)
    {
        $this->sliderRepository = $sliderRepository;
    }

    public function saveSlider(array $data, $image = null)
    {
        return $this->sliderRepository->saveSlider($data, $image);
    }

    public function updateSlider(array $data, $image = null, $id)
    {
        return $this->sliderRepository->updateSlider($data, $image, $id);
    }
}
