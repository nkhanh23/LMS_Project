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
        Schema::create('course_quality_checks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('course_id');
            //key check
            $table->string('check_key');
            //status
            $table->string('status')->default('fail'); // pass, fail, warning
            //message
            $table->text('message')->nullable();
            //reviewed by
            $table->unsignedBigInteger('reviewed_by')->nullable();
            $table->timestamps();

            $table->index(['course_id', 'check_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_quality_checks');
    }
};
