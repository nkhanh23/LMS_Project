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
        Schema::create('quiz_attempt_answers', function (Blueprint $table) {
            $table->id();

            //đáp án này thuộc lần làm bài nào.
            $table->unsignedBigInteger('attempt_id');
            //đáp án này thuộc câu hỏi nào.
            $table->unsignedBigInteger('question_id');
            //Student chọn option nào.
            $table->unsignedBigInteger('selected_option_id')->nullable();

            //Đáp án đúng hay sai.
            $table->boolean('is_correct')->default(false);

            $table->timestamps();

            // khóa ngoại
            $table->foreign('attempt_id')->references('id')->on('quiz_attempts')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('quiz_questions')->onDelete('cascade');
            $table->foreign('selected_option_id')->references('id')->on('quiz_options')->onDelete('cascade');

            // index
            $table->index('attempt_id');
            $table->index('question_id');
            $table->index('selected_option_id');
            $table->index(['attempt_id', 'question_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_attempt_answers');
    }
};
