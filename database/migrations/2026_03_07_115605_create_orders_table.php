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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            // khóa ngoại
            $table->integer('payment_id');
            $table->integer('user_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('instructor_id')->nullable();
            $table->string('course_title')->nullable();
            $table->integer('price')->nullable();
            // trạng thái đơn hàng
            $table->string('status')->default('completed');
            // trạng thái refund
            $table->string('refund_status')->default('none');
            // số tiền refund
            $table->decimal('refund_amount', 12, 2)->default(0);
            // lý do refund
            $table->text('refund_reason')->nullable();
            // lý do hủy
            $table->text('cancel_reason')->nullable();
            // thời gian yêu cầu refund
            $table->timestamp('refund_requested_at')->nullable();
            // thời gian refund
            $table->timestamp('refunded_at')->nullable();
            // người refund
            $table->unsignedBigInteger('refunded_by')->nullable();
            // thời gian hủy
            $table->timestamp('cancelled_at')->nullable();
            // người hủy
            $table->unsignedBigInteger('cancelled_by')->nullable();
            // thời gian thu hồi quyền truy cập
            $table->timestamp('access_revoked_at')->nullable();
            // thời gian thanh toán
            $table->timestamp('paid_at')->nullable();
            // tổng số tiền
            $table->decimal('gross_amount', 12, 2)->nullable();
            // số tiền ròng
            $table->decimal('net_amount', 12, 2)->nullable();
            // số tiền nền tảng
            $table->decimal('platform_amount', 12, 2)->default(0);
            $table->timestamps();

            //index
            $table->index('instructor_id');
            $table->index('course_id');
            $table->index('payment_id');
            $table->index('status');
            $table->index('paid_at');
            $table->index(['status', 'refund_status']);
            $table->index(['user_id', 'course_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
