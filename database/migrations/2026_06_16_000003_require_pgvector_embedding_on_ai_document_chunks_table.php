<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('ai_document_chunks')) {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        $column = DB::selectOne(
            "select udt_name from information_schema.columns where table_name = ? and column_name = ?",
            ['ai_document_chunks', 'embedding']
        );

        if ($column?->udt_name !== 'vector') {
            DB::statement('ALTER TABLE ai_document_chunks DROP COLUMN IF EXISTS embedding');
            DB::statement('ALTER TABLE ai_document_chunks ADD COLUMN embedding vector(768)');
        }

        DB::statement('DROP INDEX IF EXISTS ai_document_chunks_embedding_hnsw_idx');
        DB::statement("
            CREATE INDEX ai_document_chunks_embedding_hnsw_idx
            ON ai_document_chunks
            USING hnsw (embedding vector_cosine_ops)
        ");
    }

    public function down(): void
    {
        DB::statement('DROP INDEX IF EXISTS ai_document_chunks_embedding_hnsw_idx');
        DB::statement('ALTER TABLE ai_document_chunks DROP COLUMN IF EXISTS embedding');
    }
};
