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
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();

            //Quiz thuộc course nào.
            $table->unsignedBigInteger('course_id');
            //Quiz thuộc section nào.
            $table->unsignedBigInteger('section_id')->nullable();
            //Quiz thuộc lecture nào.
            $table->unsignedBigInteger('lecture_id')->unique();

            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('source_type', 20)->default('manual'); // manual | import | ai
            //Giới hạn thời gian làm quiz, tính bằng phút.
            $table->unsignedInteger('time_limit')->nullable()->comment('minutes');
            //Điểm đạt yêu cầu.
            $table->unsignedInteger('passing_score')->default(0)->comment('percentage or score tùy cách chấm');
            //Số lần thử tối đa.
            $table->unsignedInteger('max_attempts')->nullable();

            //Trộn câu hỏi.
            $table->boolean('shuffle_questions')->default(false);
            //Hiển thị kết quả ngay lập tức.
            $table->boolean('show_result_immediately')->default(true);
            //Trạng thái.
            $table->boolean('is_active')->default(true);

            $table->timestamps();

            // khóa ngoại
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('course_sections')->onDelete('cascade');
            $table->foreign('lecture_id')->references('id')->on('course_lectures')->onDelete('cascade');

            // index
            $table->index('course_id');
            $table->index('section_id');
            $table->index('source_type');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
