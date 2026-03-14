<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LectureNoteRequest;
use App\Models\CourseLecture;
use App\Services\LectureNoteService;
use Illuminate\Support\Facades\Auth;

class LectureNoteController extends Controller
{
    protected $noteService;

    public function __construct(LectureNoteService $noteService)
    {
        $this->noteService = $noteService;
    }

    public function index(CourseLecture $lecture)
    {
        $notes = $this->noteService->getByLectureAndUser($lecture->id, Auth::id());

        return response()->json([
            'status' => 'success',
            'notes' => $notes,
        ]);
    }

    public function store(LectureNoteRequest $request)
    {
        $result = $this->noteService->store($request->validated(), Auth::id());

        $note = $result['note'];

        $html = view('frontend.pages.learning.partials.note-item', [
            'note' => $note,
        ])->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã thêm ghi chú.',
            'html' => $html,
        ]);
    }

    public function update(LectureNoteRequest $request, int $id)
    {
        $result = $this->noteService->update($id, $request->validated(), Auth::id());

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        $html = view('frontend.pages.learning.partials.note-item', [
            'note' => $result['note'],
        ])->render();

        return response()->json([
            'status' => 'success',
            'message' => 'Đã cập nhật ghi chú.',
            'html' => $html,
        ]);
    }

    public function destroy(int $id)
    {
        $result = $this->noteService->delete($id, Auth::id());

        if ($result['status'] === 'error') {
            return response()->json($result, $result['code']);
        }

        return response()->json($result);
    }
}
