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
        if (!Schema::hasTable('refund_requests')) {
            Schema::create('refund_requests', function (Blueprint $table) {
                $table->id();
                // khóa ngoại
                $table->unsignedBigInteger('order_id');
                $table->unsignedBigInteger('payment_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('instructor_id')->nullable()->index();
                // request source
                $table->string('request_source')->default('user'); // user|admin
                // loại
                $table->string('type')->default('refund'); // refund|cancel
                // trạng thái
                $table->string('status')->default('pending'); // pending|approved|rejected|processed
                // số tiền yêu cầu
                $table->decimal('requested_amount', 12, 2)->nullable();
                // số tiền được duyệt
                $table->decimal('approved_amount', 12, 2)->nullable();
                // lý do
                $table->text('reason')->nullable();
                // ghi chú của admin
                $table->text('admin_note')->nullable();
                // mã giao dịch
                $table->string('provider_ref')->nullable();
                // thời gian yêu cầu
                $table->timestamp('requested_at')->nullable();
                // người duyệt
                $table->unsignedBigInteger('reviewed_by')->nullable();
                // thời gian duyệt
                $table->timestamp('reviewed_at')->nullable();
                // người xử lý
                $table->unsignedBigInteger('processed_by')->nullable();
                // thời gian xử lý
                $table->timestamp('processed_at')->nullable();

                $table->timestamps();

                $table->index(['order_id', 'status']);
                $table->index(['user_id', 'status']);
                $table->index(['payment_id']);

                $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
                $table->foreign('payment_id')->references('id')->on('payments')->nullOnDelete();
                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('instructor_id')->references('id')->on('users')->nullOnDelete();
                $table->foreign('reviewed_by')->references('id')->on('users')->nullOnDelete();
                $table->foreign('processed_by')->references('id')->on('users')->nullOnDelete();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refund_requests');
    }
};
