<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('ai_document_chunks')) {
            return;
        }

        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        Schema::create('ai_document_chunks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('document_id')->constrained('ai_documents')->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecture_id')->nullable()->constrained('course_lectures')->nullOnDelete();

            $table->unsignedInteger('chunk_index');
            $table->text('content');
            $table->unsignedInteger('content_length')->default(0);
            $table->json('meta_json')->nullable();

            $table->timestamps();

            $table->index(['document_id', 'chunk_index']);
            $table->index(['course_id', 'lecture_id']);
        });

        DB::statement("
            ALTER TABLE ai_document_chunks
            ADD COLUMN embedding vector(768),
            ADD COLUMN embedding_provider varchar(100),
            ADD COLUMN embedding_model varchar(150),
            ADD COLUMN embedding_status varchar(30) DEFAULT 'pending' NOT NULL,
            ADD COLUMN embedding_error text,
            ADD COLUMN external_vector_id varchar(255)
        ");

        DB::statement("
            CREATE INDEX ai_document_chunks_tsv_idx
            ON ai_document_chunks
            USING GIN (to_tsvector('simple', content))
        ");

        DB::statement("
            CREATE INDEX ai_document_chunks_embedding_hnsw_idx
            ON ai_document_chunks
            USING hnsw (embedding vector_cosine_ops)
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_document_chunks');
    }
};
