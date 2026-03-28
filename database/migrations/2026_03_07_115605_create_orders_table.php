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
            //  =========================== KHÓA NGOẠI ===========================
            //Thuộc lần thanh toán nào
            $table->integer('payment_id');
            //Thuộc user nào
            $table->integer('user_id')->nullable();
            //Thuộc khoá học nào
            $table->integer('course_id')->nullable();
            //Thuộc giảng viên nào
            $table->integer('instructor_id')->nullable();
            //Tên khoá học
            $table->string('course_title')->nullable();
            //Giá khoá học
            $table->integer('price')->nullable();
            // =========================== TRẠNG THÁI ĐƠN HÀNG ===========================
            // trạng thái đơn hàng
            $table->string('status')->default('completed'); // completed | cancelled | pending
            // trạng thái refund
            $table->string('refund_status')->default('none'); // none | pending | approved | rejected
            // =========================== THÔNG TIN HOÀN TIỀN ===========================
            // số tiền refund
            $table->decimal('refund_amount', 12, 2)->default(0);
            // lý do refund
            $table->text('refund_reason')->nullable();
            // =========================== HỦY ĐƠN ===========================
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
            // =========================== QUYỀN TRUY CẬP ===========================
            // thời gian thu hồi quyền truy cập
            $table->timestamp('access_revoked_at')->nullable();
            // =========================== TÀI CHÍNH ===========================
            // thời gian thanh toán
            $table->timestamp('paid_at')->nullable();
            // tổng số tiền
            $table->decimal('gross_amount', 12, 2)->nullable();
            // số tiền ròng
            $table->decimal('net_amount', 12, 2)->nullable();
            // số tiền nền tảng
            $table->decimal('platform_amount', 12, 2)->default(0);
            $table->timestamps();

            // =========================== INDEX tối ưu truy vấn ===========================
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
