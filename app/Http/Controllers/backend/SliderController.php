<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\SliderRequest;
use App\Models\Slider;
use App\Service\SliderService;
use Illuminate\Http\Request;

class SliderController extends Controller
{

    protected $sliderService;
    public function __construct(SliderService $sliderService)
    {
        $this->sliderService = $sliderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $all_sliders = Slider::latest()->get();
        return view('backend.admin.slider.index', compact('all_sliders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backend.admin.slider.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SliderRequest $request)
    {
        //Pass dữ liệu và file sang service
        $this->sliderService->saveSlider($request->all(), $request->file('image'));
        return redirect()->back()->with('success', 'Danh mục đã được thêm thành công');
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
        $slider = Slider::find($id);
        return view('backend.admin.slider.edit', compact('slider'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SliderRequest $request, string $id)
    {
        $this->sliderService->updateSlider($request->all(), $request->file('image'), $id);
        return redirect()->route('admin.slider.index')->with('success', 'Danh mục đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $slider = Slider::findOrFail($id);
        if ($slider->image) {
            $imagePath = public_path(parse_url($slider->image, PHP_URL_PATH));
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        $slider->delete();
        return redirect()->route('admin.slider.index')->with('success', 'Danh mục đã được xóa thành công');
    }
}
