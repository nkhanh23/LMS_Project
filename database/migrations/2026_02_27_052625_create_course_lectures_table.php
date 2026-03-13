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
        Schema::create('course_lectures', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('section_id')->nullable();
            $table->string('lecture_title')->nullable();
            $table->string('type', 20)->default('video');
            $table->string('url')->nullable();
            $table->string('file_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->unsignedBigInteger('file_size')->nullable();
            $table->string('storage_disk')->nullable();
            $table->text('content')->nullable();
            $table->decimal('video_duration', 8, 2)->nullable();
            $table->timestamps();

            // foreign key
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('course_sections')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_lectures');
    }
};
