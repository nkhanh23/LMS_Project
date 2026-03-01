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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id');
            $table->integer('subcategory_id');
            $table->integer('instructor_id');
            $table->string('course_image')->nullable();
            $table->text('course_title')->nullable();
            $table->text('course_name')->nullable();
            $table->text('course_name_slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('video_url')->nullable();
            $table->string('label')->nullable();
            $table->string('duration')->nullable();
            $table->string('resources')->nullable();
            $table->string('certificate')->nullable();
            $table->integer('selling_price')->nullable();
            $table->integer('discount_price')->nullable();
            $table->text('prerequisites')->nullable();
            $table->string('bestseller')->nullable();
            $table->string('featured')->nullable();
            $table->string('highestrated')->nullable();
            $table->json('course_goals')->nullable();
            $table->tinyInteger('status')->default(0)->comment('0=inactive, 1=active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
