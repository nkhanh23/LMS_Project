<?php

namespace App\Services;

use App\Models\AiDocument;
use App\Models\AiDocumentChunk;
use Illuminate\Support\Facades\DB;
use Throwable;

class AiDocumentIndexService
{
    public function __construct(
        protected AiChunkingService $chunkingService,
        protected AiDocumentExtractionService $extractionService,
        protected AiEmbeddingService $embeddingService,
    ) {
    }

    public function process(AiDocument $document): void
    {
        $document->update([
            'index_status' => 'extracting',
            'index_error' => null,
        ]);

        $preparedText = $this->prepareTextForIndexing(
            $this->extractionService->extract($document)
        );

        if ($preparedText === '') {
            throw new \RuntimeException('Tài liệu không có nội dung hợp lệ để index.');
        }

        $document->update([
            'extracted_text' => $preparedText,
            'index_status' => 'chunking',
            'index_error' => null,
        ]);

        $createdChunkIds = DB::transaction(function () use ($document, $preparedText) {
            AiDocumentChunk::query()
                ->where('document_id', $document->id)
                ->delete();

            $chunks = $this->chunkingService->splitText($preparedText);

            if (empty($chunks)) {
                throw new \RuntimeException('Không tạo được chunk nào từ tài liệu.');
            }

            $ids = [];

            foreach ($chunks as $chunk) {
                $created = AiDocumentChunk::query()->create([
                    'document_id' => $document->id,
                    'course_id' => $document->course_id,
                    'lecture_id' => $document->lecture_id,
                    'chunk_index' => $chunk['chunk_index'],
                    'content' => $chunk['content'],
                    'content_length' => $chunk['content_length'],
                    'meta_json' => array_merge(
                        $chunk['meta_json'] ?? [],
                        [
                            'document_title' => $document->title,
                            'source_type' => $document->source_type,
                            'language' => $document->language,
                        ]
                    ),
                    'embedding_status' => 'pending',
                ]);

                $ids[] = $created->id;
            }

            return $ids;
        });

        $document->update([
            'index_status' => 'embedding',
            'index_error' => null,
        ]);

        foreach ($createdChunkIds as $chunkId) {
            $chunk = AiDocumentChunk::query()->findOrFail($chunkId);

            $chunk->update([
                'embedding_status' => 'processing',
                'embedding_error' => null,
            ]);

            $embedding = $this->embeddingService->embedDocumentChunk(
                text: $chunk->content,
                title: $document->title
            );

            $chunk->update([
                'embedding' => $this->vectorLiteral($embedding['values']),
                'embedding_provider' => $embedding['provider'],
                'embedding_model' => $embedding['model'],
                'embedding_status' => 'ready',
                'embedding_error' => null,
            ]);
        }

        $document->update([
            'index_status' => 'indexed',
            'index_error' => null,
            'indexed_at' => now(),
        ]);
    }

    public function safeProcess(AiDocument $document): void
    {
        try {
            $this->process($document->fresh());
        } catch (Throwable $e) {
            $document->update([
                'index_status' => 'failed',
                'index_error' => $e->getMessage(),
                'indexed_at' => null,
            ]);

            throw $e;
        }
    }

    public function prepareTextForIndexing(string $text): string
    {
        $text = str_replace(["\r\n", "\r"], "\n", $text);
        $text = strip_tags($text);
        $text = preg_replace('/[ \t]+/u', ' ', $text);
        $text = preg_replace('/\n{3,}/u', "\n\n", $text);

        return trim((string) $text);
    }

    public function vectorLiteral(array $values): string
    {
        return '[' . implode(',', array_map(
            fn ($v) => is_float($v) || is_int($v) ? (string) $v : (string) ((float) $v),
            $values
        )) . ']';
    }
}
