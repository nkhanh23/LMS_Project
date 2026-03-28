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
        Schema::create('enrollments', function (Blueprint $table) {
            $table->id();

            // Khóa ngoại
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained('courses')->cascadeOnDelete();

            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            // Nguồn đăng ký order (mua), admin (admin cấp), manual, coupon
            $table->enum('source', ['order', 'admin', 'manual', 'coupon'])->default('order');
            // Trạng thái
            $table->enum('status', ['active', 'completed', 'revoked', 'refunded'])->default('active');

            // Thời gian truy cập
            $table->timestamp('access_granted_at')->nullable();
            $table->timestamp('access_expires_at')->nullable();

            // Bài học cuối cùng
            $table->foreignId('last_lecture_id')->nullable()->constrained('course_lectures')->nullOnDelete();
            $table->timestamp('last_accessed_at')->nullable();

            // Thời gian hoàn thành
            $table->timestamp('completed_at')->nullable();
            // Thời gian thu hồi
            $table->timestamp('revoked_at')->nullable();
            // Lý do thu hồi
            $table->text('revoked_reason')->nullable();

            $table->timestamps();

            // Unique constraint
            $table->unique(['user_id', 'course_id']);
            // Index
            $table->index(['course_id', 'status']);
            $table->index(['user_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('enrollments');
    }
};
