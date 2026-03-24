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
        Schema::create('course_progress', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại
            $table->foreignId('enrollment_id')->constrained('enrollments')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();

            // Tổng số bài học
            $table->unsignedInteger('total_lectures')->default(0);
            // Số bài học đã hoàn thành
            $table->unsignedInteger('completed_lectures')->default(0);
            // Phần trăm hoàn thành
            $table->unsignedInteger('completion_percent')->default(0);

            // Bài học cuối cùng
            $table->foreignId('last_lecture_id')->nullable()->constrained('course_lectures')->nullOnDelete();
            // Thời gian hoạt động cuối cùng
            $table->timestamp('last_activity_at')->nullable();
            // Thời gian hoàn thành
            $table->timestamp('completed_at')->nullable();

            $table->timestamps();

            // Unique constraint
            $table->unique(['enrollment_id']);
            // Index
            $table->index(['course_id', 'completion_percent']);
            $table->index(['user_id', 'completion_percent']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_progress');
    }
};
