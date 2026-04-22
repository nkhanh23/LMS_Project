<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('CREATE EXTENSION IF NOT EXISTS vector');

        DB::statement("
            ALTER TABLE ai_document_chunks
            ADD COLUMN IF NOT EXISTS embedding vector(768),
            ADD COLUMN IF NOT EXISTS embedding_provider varchar(100),
            ADD COLUMN IF NOT EXISTS embedding_model varchar(150),
            ADD COLUMN IF NOT EXISTS embedding_status varchar(30) DEFAULT 'pending' NOT NULL,
            ADD COLUMN IF NOT EXISTS embedding_error text,
            ADD COLUMN IF NOT EXISTS external_vector_id varchar(255)
        ");

        DB::statement("
            CREATE INDEX IF NOT EXISTS ai_document_chunks_course_lecture_idx
            ON ai_document_chunks (course_id, lecture_id)
        ");

        DB::statement("
            CREATE INDEX IF NOT EXISTS ai_document_chunks_embedding_hnsw_idx
            ON ai_document_chunks
            USING hnsw (embedding vector_cosine_ops)
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_document_chunks', function (Blueprint $table) {
            //
        });
    }
};
