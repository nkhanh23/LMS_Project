<?php

namespace App\Services;

use App\Models\AiDocument;
use App\Models\AiDocumentChunk;
use Illuminate\Support\Facades\DB;
use Throwable;

class AiDocumentIndexService
{
    public function __construct(
        protected AiChunkingService $chunkingService
    ) {}

    public function reindex(AiDocument $document): void
    {
        DB::transaction(function () use ($document) {
            $document->update([
                'index_status' => 'processing',
                'index_error' => null,
            ]);

            AiDocumentChunk::query()
                ->where('document_id', $document->id)
                ->delete();

            $chunks = $this->chunkingService->splitText((string) $document->extracted_text);

            foreach ($chunks as $chunk) {
                AiDocumentChunk::query()->create([
                    'document_id' => $document->id,
                    'course_id' => $document->course_id,
                    'lecture_id' => $document->lecture_id,
                    'chunk_index' => $chunk['chunk_index'],
                    'content' => $chunk['content'],
                    'content_length' => $chunk['content_length'],
                    'meta_json' => $chunk['meta_json'],
                ]);
            }

            $document->update([
                'index_status' => 'indexed',
                'indexed_at' => now(),
            ]);
        });
    }

    public function safeReindex(AiDocument $document): void
    {
        try {
            $this->reindex($document);
        } catch (Throwable $e) {
            $document->update([
                'index_status' => 'failed',
                'index_error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }
}
