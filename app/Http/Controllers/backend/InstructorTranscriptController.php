<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateTranscriptJob;
use App\Models\CourseLecture;
use App\Models\TranscriptJob;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InstructorTranscriptController extends Controller
{
    public function generate(Request $request, CourseLecture $lecture): RedirectResponse
    {
        $user = Auth::user();

        if (!$lecture->course || (int) $lecture->course->instructor_id !== (int) $user->id) {
            abort(403, 'Bạn không có quyền tạo transcript cho bài học này.');
        }

        if (!in_array($lecture->type, ['video', 'r2_video'], true)) {
            return back()->withErrors([
                'transcript' => 'Chỉ bài học video mới tạo được transcript.',
            ]);
        }

        $job = TranscriptJob::query()->create([
            'lecture_id' => $lecture->id,
            'course_id' => $lecture->course_id,
            'requested_by' => $user->id,
            'status' => 'queued',
            'progress' => 0,
            'request_payload' => [
                'lecture_type' => $lecture->type,
                'video_source' => $lecture->url,
                'storage_disk' => $lecture->storage_disk,
                'file_name' => $lecture->file_name,
            ],
        ]);

        GenerateTranscriptJob::dispatch($job->id);

        return back()->with('success', 'Đã đưa yêu cầu tạo transcript vào hàng đợi.');
    }

    public function show(TranscriptJob $transcriptJob)
    {
        $user = Auth::user();

        if ((int) $transcriptJob->course?->instructor_id !== (int) $user->id) {
            abort(403);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $transcriptJob->id,
                'lecture_id' => $transcriptJob->lecture_id,
                'course_id' => $transcriptJob->course_id,
                'document_id' => $transcriptJob->document_id,
                'status' => $transcriptJob->status,
                'progress' => $transcriptJob->progress,
                'error_message' => $transcriptJob->error_message,
                'started_at' => optional($transcriptJob->started_at)->toDateTimeString(),
                'finished_at' => optional($transcriptJob->finished_at)->toDateTimeString(),
            ],
        ]);
    }
}
