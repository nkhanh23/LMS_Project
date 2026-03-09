<?php

namespace App\Repositories;

use App\Models\SubCategory;

class SubCategoryRepository
{
    public function saveSubCategory($data)
    {
        $subCategory = new SubCategory();

        $subCategory->create($data);
        return $subCategory;
    }

    public function updateSubCategory($data, $id)
    {
        $subCategory = SubCategory::find($id);
        $subCategory->update($data);
        return $subCategory;
    }

    public function getAllSubCategories($search = null, $categoryId = null, $perPage = 10)
    {
        return SubCategory::when($search, function ($query, $search) {
            // Tìm kiếm theo tên hoặc slug
            return $query->where('name', 'LIKE', "%{$search}%")
                ->orWhere('slug', 'LIKE', "%{$search}%");
        })->when($categoryId, function ($query, $categoryId) {
            // Tìm kiếm theo danh mục
            return $query->where('category_id', $categoryId);
        })->with('category')->orderBy('name', 'asc')->paginate($perPage);
    }
}
