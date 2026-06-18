<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class WebsiteKnowledgeDocumentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $document = $this->route('website_kb');
        $documentId = $document?->id;

        return [
            'title' => 'required|string|max:255',
            'slug' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('website_knowledge_documents', 'slug')->ignore($documentId),
            ],
            'doc_type' => ['required', Rule::in(['feature_how_to', 'faq', 'policy'])],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'sort_order' => 'nullable|integer|min:0',
            'markdown_file' => 'nullable|file|mimes:md,txt|max:10240',
            'content_markdown' => 'nullable|string',
        ];
    }

    public function after(): array
    {
        return [
            function ($validator) {
                $hasFile = $this->hasFile('markdown_file');
                $hasContent = trim((string) $this->input('content_markdown')) !== '';

                if (! $hasFile && ! $hasContent) {
                    $validator->errors()->add('content_markdown', 'Vui long upload file Markdown hoac nhap noi dung.');
                }
            },
        ];
    }
}
