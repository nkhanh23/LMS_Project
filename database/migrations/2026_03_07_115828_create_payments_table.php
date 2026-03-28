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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            // phương thức thanh toán
            $table->string('cash_delivery')->nullable();
            // tổng số tiền
            $table->string('total_amount')->nullable();
            // số tiền hoàn tiền
            $table->decimal('refunded_amount', 12, 2)->default(0);
            // thời gian hoàn tiền
            $table->timestamp('refunded_at')->nullable();
            // tham chiếu hoàn tiền
            $table->string('refund_reference')->nullable();
            // Dữ liệu thô trả về từ Stripe
            $table->json('provider_payload')->nullable();
            // Trạng thái thanh toán (pending, success, failed, refunded)
            $table->string('provider_status')->nullable();

            $table->index(['status']);
            $table->index(['transaction_id']);
            // Loại thanh toán
            $table->string('payment_type')->nullable();
            // Số hoá đơn
            $table->string('invoice_no')->nullable();
            // Ngày đặt hàng
            $table->string('order_date')->nullable();
            // Tháng đặt hàng
            $table->string('order_month')->nullable();
            // Năm đặt hàng
            $table->string('order_year')->nullable();
            // Trạng thái thanh toán
            $table->string('status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
