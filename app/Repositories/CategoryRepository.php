<?php

namespace App\Repositories;

use App\Models\Category;
use App\Traits\FileUploadTrait;

class CategoryRepository
{
    use FileUploadTrait;

    public function getAllCategories($search = null, $perPage = 10)
    {
        return Category::when($search, function ($query, $search) {
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%");
        })->orderBy('name', 'asc')->paginate($perPage);
    }

    public function saveCategory($data, $image)
    {
        $category = new Category();

        //Xử lý upload file
        if ($image) {
            $data['image'] = $this->uploadFile($image, 'category', $category->image);
        }
        $category->create($data);
        return $category;
    }

    public function updateCategory($data, $image, $id)
    {
        $category = Category::find($id);
        if ($image) {
            $data['image'] = $this->uploadFile($image, 'category', $category->image);
        }
        $category->update($data);
        return $category;
    }
}
