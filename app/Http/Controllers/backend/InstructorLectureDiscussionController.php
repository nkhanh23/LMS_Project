<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Services\InstructorLectureDiscussionService;
use Illuminate\Http\Request;

class InstructorLectureDiscussionController extends Controller
{
    protected $instructorLectureDiscussionService;
    public function __construct(InstructorLectureDiscussionService $instructorLectureDiscussionService)
    {
        $this->instructorLectureDiscussionService = $instructorLectureDiscussionService;
    }

    //Danh sách thảo luận
    public function index(Request $request)
    {
        $instructorId = auth()->id();

        $filters = [
            'course_id'    => $request->course_id,
            'lecture_id'   => $request->lecture_id,
            'is_approved'  => $request->is_approved,
            'keyword'      => $request->keyword,
            'parent_type'  => $request->parent_type,
        ];

        $discussions = $this->instructorLectureDiscussionService
            ->getDiscussionList($instructorId, $filters);

        $filterData = $this->instructorLectureDiscussionService
            ->getFilterData($instructorId, $request->course_id);

        return view('backend.instructor.discussion.index', compact(
            'discussions',
            'filterData',
            'filters'
        ));
    }

    //Lấy danh sách bài học theo khóa học
    public function getLecturesByCourse(Request $request)
    {
        $request->validate([
            'course_id' => ['required', 'integer'],
        ]);

        $lectures = $this->instructorLectureDiscussionService
            ->getLecturesByCourse(auth()->id(), (int) $request->course_id);

        return response()->json([
            'status' => true,
            'data' => $lectures,
        ]);
    }

    //Chi tiết thảo luận
    public function show(int $id)
    {
        $discussion = $this->instructorLectureDiscussionService
            ->getDiscussionDetail($id, auth()->id());

        return view('backend.instructor.discussion.show', compact('discussion'));
    }

    //Duyệt thảo luận
    public function approve(int $id)
    {
        $this->instructorLectureDiscussionService
            ->approveDiscussion($id, auth()->id());

        return redirect()->back()->with('success', 'Comment đã được duyệt.');
    }

    //Bỏ duyệt thảo luận
    public function unapprove(int $id)
    {
        $this->instructorLectureDiscussionService
            ->unapproveDiscussion($id, auth()->id());

        return redirect()->back()->with('success', 'Comment đã được ẩn.');
    }

    //Xóa thảo luận
    public function destroy(int $id)
    {
        $this->instructorLectureDiscussionService
            ->deleteDiscussion($id, auth()->id());

        return redirect()->back()->with('success', 'Comment đã được xóa.');
    }

    //Trả lời thảo luận
    public function reply(Request $request, int $id)
    {
        $request->validate([
            'content' => ['required', 'string', 'max:3000'],
        ]);

        $this->instructorLectureDiscussionService
            ->replyDiscussion($id, auth()->id(), $request->only('content'));

        return redirect()->back()->with('success', 'Đã phản hồi comment.');
    }
}
