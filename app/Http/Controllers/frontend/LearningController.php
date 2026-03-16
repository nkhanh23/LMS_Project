<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\CourseLecture;
use App\Models\CourseSection;
use App\Models\LectureDiscussion;
use App\Models\LectureNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearningController extends Controller
{
    public function playCourse($slug)
    {
        $course = Course::where('course_name_slug', $slug)->firstOrFail();
        $firstLecture = CourseLecture::where('course_id', $course->id)->orderBy('id', 'asc')->first();

        if (!$firstLecture) {
            return back()->with('error', 'Khóa học này chưa có bài giảng nào.');
        }

        return redirect()->route('course.lecture.watch', [$slug, $firstLecture->id]);
    }

    public function watchLecture($slug, $lecture_id)
    {
        // Lấy dữ liệu khóa học
        $course = Course::where('course_name_slug', $slug)->first();

        if (!$course) {
            abort(404);
        }

        // Lấy dữ liệu bài học hiện tại và kiểm tra tính hợp lệ
        $currentLecture = CourseLecture::with('course.instructor', 'section')
            ->where('id', $lecture_id)
            ->where('course_id', $course->id)
            ->first();

        if (!$currentLecture) {
            // Nếu không tìm thấy bài giảng cụ thể, quay về bài giảng đầu tiên của khóa
            return redirect()->route('course.play', $slug);
        }

        // Lấy toàn bộ nội dung khóa học để hiển thị sidebar
        $sections = CourseSection::where('course_id', $course->id)->with('lecture')->get();

        $discussions = LectureDiscussion::with('user')
            ->where('lecture_id', $currentLecture->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        // Lấy ghi chú của người dùng
        $notes = LectureNote::where('lecture_id', $currentLecture->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        // Trả về view
        return view('frontend.pages.learning.index', compact('course', 'sections', 'currentLecture', 'discussions', 'notes'));
    }

    public function getLectureData(CourseLecture $lecture)
    {
        $lecture->load('course');

        $discussions = LectureDiscussion::with(['user', 'replies.user', 'replies'])
            ->where('lecture_id', $lecture->id)
            ->whereNull('parent_id')
            ->latest()
            ->paginate(10);

        $playerHtml = view('frontend.pages.learning.partials.player-content', [
            'currentLecture' => $lecture,
            'course' => $lecture->course,
        ])->render();

        $qnaHtml = view('frontend.pages.learning.partials.qna-list', [
            'discussions' => $discussions,
            'course' => $lecture->course,
            'currentLecture' => $lecture,
        ])->render();

        $notes = LectureNote::where('lecture_id', $lecture->id)
            ->where('user_id', Auth::id())
            ->latest()
            ->get();

        $notesHtml = view('frontend.pages.learning.partials.note-list', [
            'notes' => $notes,
            'course' => $lecture->course,
            'currentLecture' => $lecture,
        ])->render();

        return response()->json([
            'status' => 'success',
            'lecture' => [
                'id' => $lecture->id,
                'course_id' => $lecture->course_id,
                'title' => $lecture->lecture_title,
                'type' => $lecture->type,
                'url' => $lecture->url,
                'content' => $lecture->content,
                'video_duration' => $lecture->video_duration,
            ],
            'player_html' => $playerHtml,
            'qna_html' => $qnaHtml,
            'notes_html' => $notesHtml,
        ]);
    }
}
