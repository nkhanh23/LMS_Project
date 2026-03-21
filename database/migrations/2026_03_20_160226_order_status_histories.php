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
        Schema::create('order_status_histories', function (Blueprint $table) {
            $table->id();
            // khóa ngoại
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('payment_id')->nullable();
            // trạng thái trước
            $table->string('from_status')->nullable();
            // trạng thái sau
            $table->string('to_status')->nullable();
            // trạng thái refund trước
            $table->string('from_refund_status')->nullable();
            // trạng thái refund sau
            $table->string('to_refund_status')->nullable();
            // hành động
            $table->string('action'); // user_refund_request | admin_refund_approve | admin_refund_reject | admin_manual_refund | admin_manual_cancel
            // người thực hiện
            $table->unsignedBigInteger('actor_id')->nullable();
            // vai trò người thực hiện
            $table->string('actor_role')->nullable();
            // ghi chú
            $table->text('note')->nullable();
            // meta json
            $table->json('meta_json')->nullable();

            $table->timestamps();

            $table->index(['order_id', 'created_at']);
            $table->index(['payment_id']);
            $table->index(['action']);

            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->nullOnDelete();
            $table->foreign('actor_id')->references('id')->on('users')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_histories');
    }
};
