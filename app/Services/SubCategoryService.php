<?php

namespace App\Services;

use App\Models\SubCategory;
use App\Repositories\SubCategoryRepository;

class SubCategoryService
{
    protected $subCategoryRepository;
    public function __construct(SubCategoryRepository $subCategoryRepository)
    {
        $this->subCategoryRepository = $subCategoryRepository;
    }

    public function saveSubCategory(array $data)
    {
        return $this->subCategoryRepository->saveSubCategory($data);
    }

    public function updateSubCategory(array $data, $id)
    {
        $subCategory = SubCategory::find($id);
        $subCategory->update($data);
        return $subCategory;
    }

    public function getAllSubCategories($search = null, $categoryId = null, $perPage = 10)
    {
        return $this->subCategoryRepository->getAllSubCategories($search, $categoryId, $perPage);
    }
}
