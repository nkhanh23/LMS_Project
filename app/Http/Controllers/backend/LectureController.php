<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LectureRequest;
use App\Models\CourseLecture;
use App\Service\LectureService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;

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
        $validatedData = $request->validated();

        if ($validatedData['type'] === 'document') {
            if ($request->hasFile('document_file')) {
                $validatedData['url'] = $this->uploadFile($request->file('document_file'), 'lectures');
            } else {
                $validatedData['url'] = null;
            }
            $validatedData['video_duration'] = null;
        } elseif ($validatedData['type'] === 'text') {
            $validatedData['url'] = null;
            $validatedData['video_duration'] = null;
        }

        // Prevent Eloquent from trying to save the UploadedFile object to the database
        if (isset($validatedData['document_file'])) {
            unset($validatedData['document_file']);
        }

        $this->lectureService->createLecture($validatedData);
        return back()->with('success', 'Bài học đã được thêm thành công');
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
        $validatedData = $request->validated();
        $lecture = CourseLecture::findOrFail($id);

        if ($validatedData['type'] === 'document') {
            if ($request->hasFile('document_file')) {
                $validatedData['url'] = $this->uploadFile($request->file('document_file'), 'lectures', $lecture->url);
            } else {
                // Keep existing URL if it was already a document, otherwise clear it to avoid YouTube URL leakage
                $validatedData['url'] = $lecture->type === 'document' ? $lecture->url : null;
            }
            $validatedData['video_duration'] = null;
        } elseif ($validatedData['type'] === 'text') {
            $validatedData['url'] = null;
            $validatedData['video_duration'] = null;
        }

        // Prevent Eloquent from trying to save the UploadedFile object to the database
        if (isset($validatedData['document_file'])) {
            unset($validatedData['document_file']);
        }

        $this->lectureService->updateLecture($validatedData, $id);
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
