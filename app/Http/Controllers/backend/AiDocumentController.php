<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiDocumentStoreRequest;
use App\Jobs\ProcessAiDocumentJob;
use App\Models\AiDocument;
use App\Services\AiDocumentIndexService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AiDocumentController extends Controller
{
    protected $aiDocumentIndexService;
    public function __construct(AiDocumentIndexService $aiDocumentIndexService)
    {
        $this->aiDocumentIndexService = $aiDocumentIndexService;
    }

    public function index()
    {
        $documents = AiDocument::query()
            ->latest()
            ->paginate(20);

        return view('backend.admin.ai-document.index', compact('documents'));
    }

    public function store(AiDocumentStoreRequest $request): RedirectResponse
    {
        $disk = env('AI_DOCUMENT_DISK', 'public');

        $storedPath = null;
        $fileName = null;
        $mimeType = null;
        $sourceType = $request->input('source_type', 'manual_upload');

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $storedPath = $file->store('ai-documents', $disk);
            $fileName = $file->getClientOriginalName();
            $mimeType = $file->getMimeType();
            $extension = strtolower($file->getClientOriginalExtension());

            $sourceType = match ($extension) {
                'pdf' => 'pdf',
                'docx' => 'docx',
                'txt' => 'txt',
                'md' => 'md',
                default => $sourceType,
            };
        }

        $document = AiDocument::query()->create([
            'course_id' => $request->integer('course_id'),
            'lecture_id' => $request->integer('lecture_id') ?: null,
            'uploaded_by' => Auth::id(),
            'title' => $request->string('title')->toString(),
            'source_type' => $sourceType,
            'file_name' => $fileName,
            'mime_type' => $mimeType,
            'storage_disk' => $storedPath ? $disk : null,
            'storage_path' => $storedPath,
            'extracted_text' => $request->input('content'),
            'language' => $request->input('language', 'vi'),
            'index_status' => 'pending',
            'index_error' => null,
            'indexed_at' => null,
        ]);

        ProcessAiDocumentJob::dispatch($document->id);

        return back()->with('success', 'Đã nhận tài liệu. Hệ thống đang xử lý nền để extract, chunk và index.');
    }

    public function reindex(AiDocument $document): RedirectResponse
    {
        $document->update([
            'index_status' => 'pending',
            'index_error' => null,
            'indexed_at' => null,
        ]);

        ProcessAiDocumentJob::dispatch($document->id);

        return back()->with('success', 'Đã đưa tài liệu vào hàng đợi re-index.');
    }

    public function destroy(AiDocument $document): RedirectResponse
    {
        if ($document->storage_disk && $document->storage_path) {
            Storage::disk($document->storage_disk)->delete($document->storage_path);
        }

        $document->chunks()->delete();
        $document->delete();

        return back()->with('success', 'Đã xóa tài liệu AI và toàn bộ chunks liên quan.');
    }
}
