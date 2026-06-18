<?php

namespace App\Http\Controllers\backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\WebsiteKnowledgeDocumentRequest;
use App\Models\WebsiteKnowledgeDocument;
use App\Services\WebsiteKnowledgeDocumentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WebsiteKnowledgeDocumentController extends Controller
{
    public function __construct(
        protected WebsiteKnowledgeDocumentService $documentService
    ) {}

    public function index(Request $request)
    {
        $search = trim($request->string('search')->toString());
        $docType = trim($request->string('doc_type')->toString());
        $status = trim($request->string('status')->toString());

        $documents = WebsiteKnowledgeDocument::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('title', 'like', '%' . $search . '%')
                        ->orWhere('slug', 'like', '%' . $search . '%')
                        ->orWhere('content_markdown', 'like', '%' . $search . '%');
                });
            })
            ->when($docType !== '', fn($query) => $query->where('doc_type', $docType))
            ->when($status !== '', fn($query) => $query->where('status', $status))
            ->latest('updated_at')
            ->paginate(10);

        return view('backend.admin.website-kb.index', [
            'documents' => $documents,
            'search' => $search,
            'docType' => $docType,
            'status' => $status,
            'docTypes' => WebsiteKnowledgeDocument::DOC_TYPES,
            'statuses' => WebsiteKnowledgeDocument::STATUSES,
        ]);
    }

    public function create()
    {
        return view('backend.admin.website-kb.create', [
            'docTypes' => WebsiteKnowledgeDocument::DOC_TYPES,
            'statuses' => WebsiteKnowledgeDocument::STATUSES,
        ]);
    }

    public function store(WebsiteKnowledgeDocumentRequest $request)
    {
        $this->documentService->store(
            data: $request->validated(),
            file: $request->file('markdown_file'),
            userId: (int) Auth::id()
        );

        return redirect()
            ->route('admin.website-kb.index')
            ->with('success', 'Da tao tai lieu knowledge base thanh cong.');
    }

    public function edit(WebsiteKnowledgeDocument $website_kb)
    {
        return view('backend.admin.website-kb.edit', [
            'document' => $website_kb,
            'docTypes' => WebsiteKnowledgeDocument::DOC_TYPES,
            'statuses' => WebsiteKnowledgeDocument::STATUSES,
        ]);
    }

    public function update(WebsiteKnowledgeDocumentRequest $request, WebsiteKnowledgeDocument $website_kb)
    {
        $this->documentService->update(
            document: $website_kb,
            data: $request->validated(),
            file: $request->file('markdown_file'),
            userId: (int) Auth::id()
        );

        return redirect()
            ->route('admin.website-kb.index')
            ->with('success', 'Da cap nhat tai lieu knowledge base thanh cong.');
    }

    public function destroy(WebsiteKnowledgeDocument $website_kb)
    {
        $this->documentService->delete($website_kb);

        return redirect()
            ->route('admin.website-kb.index')
            ->with('success', 'Da xoa tai lieu knowledge base.');
    }
}
