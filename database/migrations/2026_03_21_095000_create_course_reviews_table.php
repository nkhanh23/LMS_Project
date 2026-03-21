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
        Schema::create('course_reviews', function (Blueprint $blueprint) {
            $blueprint->id();
            $blueprint->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $blueprint->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $blueprint->foreignId('instructor_id')->constrained('users')->onDelete('cascade');
            $blueprint->integer('rating');
            $blueprint->text('comment');
            $blueprint->boolean('is_approved')->default(false);
            $blueprint->timestamps();

            // Add indexes for performance
            $blueprint->index(['course_id', 'is_approved']);
            $blueprint->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_reviews');
    }
};
