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
        Schema::create('ai_documents', function (Blueprint $table) {
            $table->id();

            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->foreignId('lecture_id')->nullable()->constrained('course_lectures')->nullOnDelete();

            //
            $table->foreignId('uploaded_by')->constrained('users')->cascadeOnDelete();

            $table->string('title');
            $table->string('source_type', 50)->default('manual_upload'); // manual_upload, transcript, lesson_content
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->string('storage_disk')->nullable();
            $table->string('storage_path')->nullable();

            $table->longText('extracted_text')->nullable();
            $table->string('language', 10)->default('vi');

            $table->string('index_status', 20)->default('pending'); // pending, processing, indexed, failed
            $table->text('index_error')->nullable();
            $table->timestamp('indexed_at')->nullable();

            $table->timestamps();

            $table->index(['course_id', 'lecture_id']);
            $table->index(['uploaded_by']);
            $table->index(['index_status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_documents');
    }
};
