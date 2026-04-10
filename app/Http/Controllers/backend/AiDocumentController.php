<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\AiDocumentStoreRequest;
use App\Models\AiDocument;
use App\Services\AiDocumentIndexService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AiDocumentController extends Controller
{
    protected $aiDocumentIndexService;
    public function __construct(AiDocumentIndexService $aiDocumentIndexService)
    {
        $this->aiDocumentIndexService = $aiDocumentIndexService;
    }


    public function store(AiDocumentStoreRequest $request): RedirectResponse
    {
        $document = AiDocument::query()->create([
            'course_id' => $request->integer('course_id'),
            'lecture_id' => $request->integer('lecture_id') ?: null,
            'uploaded_by' => Auth::id(),
            'title' => $request->string('title')->toString(),
            'source_type' => 'manual_upload',
            'extracted_text' => $request->string('content')->toString(),
            'language' => 'vi',
            'index_status' => 'pending',
        ]);

        $this->aiDocumentIndexService->safeReindex($document);

        return back()->with('success', 'Đã thêm tài liệu AI và index thành công');
    }

    public function reindex(AiDocument $document): RedirectResponse
    {
        $this->aiDocumentIndexService->safeReindex($document);

        return back()->with('success', 'Đã re-index tài liệu');
    }

    public function destroy(AiDocument $document): RedirectResponse
    {
        $document->delete();

        return back()->with('success', 'Đã xóa tài liệu AI');
    }
}
