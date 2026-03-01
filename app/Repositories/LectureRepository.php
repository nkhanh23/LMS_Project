<?php

namespace App\Repositories;

use App\Models\Category;
use App\Models\CourseLecture;

class LectureRepository
{

    public function createLecture($data)
    {
        return CourseLecture::create($data);
    }

    public function updateLecture(array $data, $id)
    {
        $lecture = CourseLecture::find($id);
        $lecture->update($data);
        return $lecture->fresh();
    }
}
