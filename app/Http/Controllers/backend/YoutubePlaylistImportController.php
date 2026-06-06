<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\YoutubePlaylistImportRequest;
use App\Models\Category;
use App\Services\YoutubePlaylistImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Throwable;

class YoutubePlaylistImportController extends Controller
{
    public function __construct(private YoutubePlaylistImportService $importService)
    {
    }

    public function create(): View
    {
        $all_categories = Category::orderBy('name')->get();

        return view('backend.instructor.course.youtube-import', compact('all_categories'));
    }

    public function store(YoutubePlaylistImportRequest $request): RedirectResponse
    {
        try {
            $course = $this->importService->import($request->validated(), Auth::id());
        } catch (Throwable $exception) {
            return back()
                ->withInput($request->except('youtube_api_key'))
                ->with('error', 'Import YouTube playlist thất bại: ' . $exception->getMessage());
        }

        return redirect()
            ->route('instructor.course-section.show', $course->id)
            ->with('success', 'Đã import playlist thành khóa học draft với ' . $course->lectures()->count() . ' bài học.');
    }
}
