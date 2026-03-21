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
            // số tiền refund
            $table->decimal('refunded_amount', 12, 2)->default(0);
            // thời gian refund
            $table->timestamp('refunded_at')->nullable();
            // tham chiếu refund
            $table->string('refund_reference')->nullable();
            // dữ liệu provider
            $table->json('provider_payload')->nullable();
            // trạng thái provider
            $table->string('provider_status')->nullable();

            $table->index(['status']);
            $table->index(['transaction_id']);
            $table->string('payment_type')->nullable();
            $table->string('invoice_no')->nullable();
            $table->string('order_date')->nullable();
            $table->string('order_month')->nullable();
            $table->string('order_year')->nullable();
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
