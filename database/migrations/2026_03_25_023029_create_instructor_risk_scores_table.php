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
        Schema::create('instructor_risk_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('instructor_id')->unique();
            // Điểm rủi ro (0-1000)
            $table->integer('risk_score')->default(0);
            // Số báo cáo đã xác nhận
            $table->integer('confirmed_reports_count')->default(0);
            // Số yêu cầu hoàn tiền
            $table->integer('refund_requests_count')->default(0);
            // Số khóa học bị từ chối
            $table->integer('rejected_courses_count')->default(0);
            // Số cảnh cáo
            $table->integer('warnings_count')->default(0);
            $table->timestamp('calculated_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('instructor_risk_scores');
    }
};
