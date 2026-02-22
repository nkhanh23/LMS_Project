<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubCategoryRequest;
use App\Models\Category;
use App\Models\SubCategory;
use App\Service\SubCategoryService;
use Illuminate\Http\Request;

class SubcategoryController extends Controller
{

    protected $subCategoryService;
    public function __construct(SubCategoryService $subCategoryService)
    {
        $this->subCategoryService = $subCategoryService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_subcategories = SubCategory::orderBy('id', 'asc')->with('category')->get();
        return view('backend.admin.subcategory.index', compact('all_subcategories'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $all_categories = Category::orderBy('id', 'asc')->get();
        return view('backend.admin.subcategory.create', compact('all_categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SubCategoryRequest $request)
    {
        //Pass dữ liệu và file sang service
        $this->subCategoryService->saveSubCategory($request->validated());
        return redirect()->back()->with('success', 'Danh mục con đã được thêm thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subCategory = SubCategory::find($id);
        $all_categories = Category::orderBy('id', 'asc')->get();
        return view('backend.admin.subcategory.edit', compact('subCategory', 'all_categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SubCategoryRequest $request, string $id)
    {
        //Pass dữ liệu và file sang service
        $this->subCategoryService->updateSubCategory($request->validated(), $id);
        return redirect()->back()->with('success', 'Danh mục con đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        SubCategory::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Danh mục con đã được xóa thành công');
    }
}
