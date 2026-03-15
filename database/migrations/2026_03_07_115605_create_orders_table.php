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
            $table->integer('payment_id');
            $table->integer('user_id')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('instructor_id')->nullable();
            $table->string('course_title')->nullable();
            $table->integer('price')->nullable();
            $table->string('status')->default('completed');
            $table->timestamp('paid_at')->nullable();
            $table->decimal('gross_amount', 12, 2)->nullable();
            $table->decimal('net_amount', 12, 2)->nullable();
            $table->decimal('platform_amount', 12, 2)->default(0);
            $table->timestamps();

            //index
            $table->index('instructor_id');
            $table->index('course_id');
            $table->index('payment_id');
            $table->index('status');
            $table->index('paid_at');
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
