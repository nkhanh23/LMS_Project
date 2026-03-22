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
        Schema::create('content_reports', function (Blueprint $table) {
            $table->id();

            $table->foreignId('reporter_id')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->foreignId('reported_user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // course_review | lecture_discussion
            $table->string('reportable_type');
            $table->unsignedBigInteger('reportable_id');

            $table->foreignId('course_id')
                ->nullable()
                ->constrained('courses')
                ->nullOnDelete();

            $table->foreignId('lecture_id')
                ->nullable()
                ->constrained('course_lectures')
                ->nullOnDelete();

            // spam | abuse | harassment | hate_speech | adult | misinformation | other
            $table->string('reason_code');
            $table->text('description')->nullable();

            // pending | reviewing | resolved | dismissed
            $table->string('status')->default('pending')->index();

            // dismiss | hide_content | delete_content | lock_course | lock_instructor
            $table->string('resolution_action')->nullable();
            $table->text('resolution_note')->nullable();

            $table->json('content_snapshot')->nullable();

            $table->foreignId('reviewed_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamp('reviewed_at')->nullable();

            $table->timestamps();

            $table->index(['reportable_type', 'reportable_id']);
            $table->index(['status', 'created_at']);
            $table->index(['reported_user_id']);
            $table->index(['course_id']);
            $table->index(['lecture_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('content_reports');
    }
};
