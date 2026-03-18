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
        Schema::create('quiz_questions', function (Blueprint $table) {
            $table->id();

            //Quiz question thuộc quiz nào.
            $table->unsignedBigInteger('quiz_id');
            //Nội dung câu hỏi.
            $table->text('question_text');
            //Loại câu hỏi.
            $table->string('question_type', 30)->default('single_choice');
            //Giải thích đáp án.
            $table->text('explanation')->nullable();

            //Điểm từng câu hỏi.
            $table->unsignedInteger('points')->default(1);
            //Thứ tự câu hỏi.
            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            // khóa ngoại
            $table->foreign('quiz_id')->references('id')->on('quizzes')->onDelete('cascade');

            // index
            $table->index('quiz_id');
            $table->index('question_type');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_questions');
    }
};
