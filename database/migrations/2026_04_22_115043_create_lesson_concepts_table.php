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
        Schema::create('lesson_concepts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('lecture_id');
            $table->unsignedBigInteger('concept_id');
            $table->timestamps();

            $table->foreign('lecture_id')
                ->references('id')
                ->on('course_lectures')
                ->onDelete('cascade');

            $table->foreign('concept_id')
                ->references('id')
                ->on('concepts')
                ->onDelete('cascade');

            $table->unique(['lecture_id', 'concept_id']);
            $table->index('lecture_id');
            $table->index('concept_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lesson_concepts');
    }
};
