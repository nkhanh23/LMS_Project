<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Jobs\GenerateTranscriptJob;
use App\Models\AiDocument;
use App\Models\CourseLecture;
use App\Models\TranscriptJob;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

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

    /**
     * Lấy nội dung transcript của một lecture.
     */
    public function getTranscript(CourseLecture $lecture): JsonResponse
    {
        $user = Auth::user();
        if (!$lecture->course || (int) $lecture->course->instructor_id !== (int) $user->id) {
            abort(403);
        }

        $document = AiDocument::query()
            ->where('lecture_id', $lecture->id)
            ->where('source_type', 'transcript')
            ->latest()
            ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Chưa có transcript cho bài học này.'], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'document_id' => $document->id,
                'title' => $document->title,
                'extracted_text' => $document->extracted_text,
                'language' => $document->language,
                'index_status' => $document->index_status,
                'char_count' => mb_strlen($document->extracted_text),
                'created_at' => $document->created_at->format('d/m/Y H:i'),
                'updated_at' => $document->updated_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Cập nhật nội dung transcript.
     */
    public function updateTranscript(Request $request, CourseLecture $lecture): JsonResponse
    {
        $user = Auth::user();
        if (!$lecture->course || (int) $lecture->course->instructor_id !== (int) $user->id) {
            abort(403);
        }

        $request->validate([
            'extracted_text' => 'required|string|min:1',
        ]);

        $document = AiDocument::query()
            ->where('lecture_id', $lecture->id)
            ->where('source_type', 'transcript')
            ->latest()
            ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy transcript.'], 404);
        }

        $document->update([
            'extracted_text' => $request->input('extracted_text'),
            'index_status' => 'pending',
            'index_error' => null,
            'indexed_at' => null,
        ]);

        // Re-index document sau khi sửa
        if (class_exists(\App\Jobs\ProcessAiDocumentJob::class)) {
            \App\Jobs\ProcessAiDocumentJob::dispatch($document->id);
        }

        return response()->json([
            'success' => true,
            'message' => 'Đã cập nhật transcript thành công. Đang re-index...',
            'data' => [
                'document_id' => $document->id,
                'char_count' => mb_strlen($request->input('extracted_text')),
                'updated_at' => $document->fresh()->updated_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Thêm transcript thủ công bằng file upload hoặc nhập text.
     */
    public function storeManual(Request $request, CourseLecture $lecture): RedirectResponse
    {
        $user = Auth::user();
        if (!$lecture->course || (int) $lecture->course->instructor_id !== (int) $user->id) {
            abort(403);
        }

        $request->validate([
            'file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:10240',
            'manual_content' => 'nullable|string|min:10',
        ]);

        if (!$request->hasFile('file') && !$request->filled('manual_content')) {
            return back()->withErrors(['transcript' => 'Vui lòng upload file hoặc nhập nội dung transcript.']);
        }

        $disk = env('AI_DOCUMENT_DISK', 'public');
        $storedPath = null;
        $fileName = null;
        $mimeType = null;
        $extractedText = null;
        $sourceType = 'manual_upload';

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $storedPath = $file->store('ai-documents', $disk);
            $fileName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());

            $sourceType = match ($extension) {
                'pdf' => 'pdf',
                'docx', 'doc' => 'docx',
                'txt' => 'txt',
                default => 'manual_upload',
            };
        } else {
            $extractedText = $request->input('manual_content');
            $sourceType = 'manual_input';
        }

        $document = AiDocument::query()->create([
            'course_id' => $lecture->course_id,
            'lecture_id' => $lecture->id,
            'uploaded_by' => $user->id,
            'title' => 'Transcript - ' . ($lecture->lecture_title ?: ('Lecture #' . $lecture->id)),
            'source_type' => $sourceType,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'storage_disk' => $storedPath ? $disk : null,
            'storage_path' => $storedPath,
            'extracted_text' => $extractedText,
            'language' => 'vi',
            'index_status' => 'pending',
            'index_error' => null,
            'indexed_at' => null,
        ]);

        if (class_exists(\App\Jobs\ProcessAiDocumentJob::class)) {
            \App\Jobs\ProcessAiDocumentJob::dispatch($document->id);
        }

        return back()->with('success', 'Đã thêm transcript thủ công. Hệ thống đang xử lý...');
    }

    /**
     * Xóa transcript document của lecture.
     */
    public function deleteTranscript(CourseLecture $lecture): JsonResponse
    {
        $user = Auth::user();
        if (!$lecture->course || (int) $lecture->course->instructor_id !== (int) $user->id) {
            abort(403);
        }

        $document = AiDocument::query()
            ->where('lecture_id', $lecture->id)
            ->where('source_type', 'transcript')
            ->latest()
            ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy transcript.'], 404);
        }

        if ($document->storage_disk && $document->storage_path) {
            Storage::disk($document->storage_disk)->delete($document->storage_path);
        }

        $document->chunks()->delete();
        $document->delete();

        return response()->json(['success' => true, 'message' => 'Đã xóa transcript thành công.']);
    }

    /**
     * Re-index transcript document.
     */
    public function reindex(CourseLecture $lecture): JsonResponse
    {
        $user = Auth::user();
        if (!$lecture->course || (int) $lecture->course->instructor_id !== (int) $user->id) {
            abort(403);
        }

        $document = AiDocument::query()
            ->where('lecture_id', $lecture->id)
            ->whereIn('source_type', ['transcript', 'pdf', 'docx', 'txt', 'manual_upload', 'manual_input'])
            ->latest()
            ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Không tìm thấy tài liệu để re-index.'], 404);
        }

        $document->update([
            'index_status' => 'pending',
            'index_error' => null,
            'indexed_at' => null,
        ]);

        if (class_exists(\App\Jobs\ProcessAiDocumentJob::class)) {
            \App\Jobs\ProcessAiDocumentJob::dispatch($document->id);
        }

        return response()->json(['success' => true, 'message' => 'Đã đưa tài liệu vào hàng đợi re-index.']);
    }
}
