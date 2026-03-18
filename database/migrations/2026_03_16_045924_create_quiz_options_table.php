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
        Schema::create('quiz_options', function (Blueprint $table) {
            $table->id();

            //Quiz option thuộc question nào.
            $table->unsignedBigInteger('question_id');
            //Nội dung đáp án.
            $table->text('option_text');
            //Đáp án đúng hay sai.
            $table->boolean('is_correct')->default(false);
            //Thứ tự đáp án.
            $table->unsignedInteger('sort_order')->default(1);

            $table->timestamps();

            // khóa ngoại
            $table->foreign('question_id')->references('id')->on('quiz_questions')->onDelete('cascade');

            // index
            $table->index('question_id');
            $table->index('is_correct');
            $table->index('sort_order');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quiz_options');
    }
};
