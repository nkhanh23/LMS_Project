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
        Schema::create('transcript_jobs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecture_id');
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('document_id')->nullable();

            $table->string('status', 30)->default('queued');
            $table->unsignedTinyInteger('progress')->default(0);
            $table->text('error_message')->nullable();

            $table->json('request_payload')->nullable();
            $table->json('response_payload')->nullable();

            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamps();

            $table->foreign('lecture_id')->references('id')->on('course_lectures')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('document_id')->references('id')->on('ai_documents')->nullOnDelete();

            $table->index(['lecture_id', 'status']);
            $table->index(['course_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transcript_jobs');
    }
};
