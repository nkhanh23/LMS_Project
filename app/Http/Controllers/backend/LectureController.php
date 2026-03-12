<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LectureRequest;
use App\Models\CourseLecture;
use App\Services\LectureService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Aws\S3\S3Client;
use Illuminate\Support\Str;

class LectureController extends Controller
{
    use FileUploadTrait;

    protected $lectureService;

    public function __construct(LectureService $lectureService)
    {
        $this->lectureService = $lectureService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(LectureRequest $request)
    {
        $this->lectureService->createLecture($request->validated());
        return back()->with('success', 'Bài học đã được thêm thành công');
    }

    //
    public function generatePresignedUrl(Request $request)
    {
        $extension = $request->input('extension', 'mp4');

        // Gọi Service xử lý logic tạo URL
        $data = $this->lectureService->generatePresignedUrl($extension);

        return response()->json($data);
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LectureRequest $request, string $id)
    {
        $this->lectureService->updateLecture($request->validated(), $id);
        return back()->with('success', 'Bài học đã được cập nhật thành công');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $lecture = CourseLecture::findOrFail($id);
        $lecture->delete();
        return redirect()->back()->with('success', 'Bài học đã được xóa thành công');
    }
}
