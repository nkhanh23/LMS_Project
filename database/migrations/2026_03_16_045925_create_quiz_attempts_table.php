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
        Schema::create('quiz_attempts', function (Blueprint $table) {
            $table->id();

            //attempt thuộc quiz nào.
            $table->unsignedBigInteger('quiz_id');
            //attempt thuộc lecture nào.
            $table->unsignedBigInteger('lecture_id');
            //attempt thuộc course nào.
            $table->unsignedBigInteger('course_id');
            //attempt thuộc user nào.
            $table->unsignedBigInteger('user_id');

            //Điểm số cuối cùng.
            $table->unsignedInteger('score')->default(0);
            //Tổng số câu hỏi.
            $table->unsignedInteger('total_questions')->default(0);
            //Số câu trả lời đúng.
            $table->unsignedInteger('correct_answers')->default(0);

            //Trạng thái.
            $table->string('status', 20)->default('submitted'); // in_progress | submitted | graded
            //Thời gian bắt đầu.
            $table->timestamp('started_at')->nullable();
            //Thời gian nộp bài.
            $table->timestamp('submitted_at')->nullable();

            $table->timestamps();

            // khóa ngoại
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');
            $table->foreign('lecture_id')->references('id')->on('course_lectures')->onDelete('cascade');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            // index
            $table->index('quiz_id');
            $table->index('lecture_id');
            $table->index('course_id');
            $table->index('user_id');
            $table->index('status');
            $table->index(['quiz_id', 'user_id']);
            $table->index(['course_id', 'user_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempts');
    }
};
