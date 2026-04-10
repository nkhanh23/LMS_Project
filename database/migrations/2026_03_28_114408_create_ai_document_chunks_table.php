<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
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

        // PostgreSQL full-text index
        DB::statement("
            CREATE INDEX ai_document_chunks_tsv_idx
            ON ai_document_chunks
            USING GIN (to_tsvector('simple', content))
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_document_chunks');
    }
};
