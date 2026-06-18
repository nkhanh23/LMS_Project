<?php

namespace App\Services;

use App\Models\WebsiteKnowledgeDocument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class WebsiteKnowledgeDocumentService
{
    public function store(array $data, ?UploadedFile $file, int $userId): WebsiteKnowledgeDocument
    {
        return WebsiteKnowledgeDocument::query()->create(
            $this->buildPayload($data, $file, null, $userId)
        );
    }

    public function update(WebsiteKnowledgeDocument $document, array $data, ?UploadedFile $file, int $userId): WebsiteKnowledgeDocument
    {
        $document->update(
            $this->buildPayload($data, $file, $document, $userId)
        );

        return $document->refresh();
    }

    public function delete(WebsiteKnowledgeDocument $document): void
    {
        if ($document->storage_disk && $document->storage_path) {
            Storage::disk($document->storage_disk)->delete($document->storage_path);
        }

        $document->delete();
    }

    private function buildPayload(array $data, ?UploadedFile $file, ?WebsiteKnowledgeDocument $document, int $userId): array
    {
        $markdown = trim((string) ($data['content_markdown'] ?? ''));
        $storageDisk = $document?->storage_disk;
        $storagePath = $document?->storage_path;
        $fileName = $document?->file_name;
        $sourceType = $document?->source_type ?? 'manual';

        if ($file) {
            if ($document?->storage_disk && $document->storage_path) {
                Storage::disk($document->storage_disk)->delete($document->storage_path);
            }

            $storageDisk = env('AI_DOCUMENT_DISK', 'public');
            $storagePath = $file->store('website-kb', $storageDisk);
            $fileName = $file->getClientOriginalName();
            $markdown = $this->readMarkdownFile($file);
            $sourceType = 'upload';
        }

        $slug = trim((string) ($data['slug'] ?? ''));
        if ($slug === '') {
            $slug = Str::slug((string) $data['title']);
        }

        $status = (string) ($data['status'] ?? 'draft');

        return [
            'title' => trim((string) $data['title']),
            'slug' => $slug,
            'doc_type' => (string) $data['doc_type'],
            'status' => $status,
            'source_type' => $sourceType,
            'file_name' => $fileName,
            'storage_disk' => $storageDisk,
            'storage_path' => $storagePath,
            'content_markdown' => $markdown,
            'excerpt' => Str::limit(trim(strip_tags($markdown)), 180),
            'sort_order' => (int) ($data['sort_order'] ?? 0),
            'published_at' => $status === 'published' ? ($document?->published_at ?? now()) : null,
            'created_by' => $document?->created_by ?? $userId,
            'updated_by' => $userId,
        ];
    }

    private function readMarkdownFile(UploadedFile $file): string
    {
        $content = file_get_contents($file->getRealPath());

        return trim((string) $content);
    }
}
