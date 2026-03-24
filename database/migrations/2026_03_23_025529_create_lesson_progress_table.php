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
        Schema::create('lesson_progress', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();
            $table->foreignId('section_id')->nullable()->constrained('course_sections')->nullOnDelete();
            $table->foreignId('lecture_id')->constrained('course_lectures')->cascadeOnDelete();

            // Trạng thái
            $table->enum('status', ['not_started', 'in_progress', 'completed'])->default('not_started');
            // Tiến độ
            $table->unsignedInteger('progress_percent')->default(0);
            // Thời gian xem
            $table->unsignedInteger('watch_seconds')->default(0);

            // Thời gian bắt đầu
            $table->timestamp('started_at')->nullable();
            // Thời gian xem lần cuối
            $table->timestamp('last_watched_at')->nullable();
            // Thời gian hoàn thành
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Unique constraint
            $table->unique(['enrollment_id', 'lecture_id']);
            // Index
            $table->index(['user_id', 'course_id']);
            $table->index(['course_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_progress');
    }
};
