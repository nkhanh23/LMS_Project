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
        Schema::table('courses', function (Blueprint $table) {
            $table->index(['approval_status', 'status', 'category_id', 'created_at'], 'courses_publish_category_created_idx');
            $table->index(['approval_status', 'status', 'instructor_id', 'created_at'], 'courses_publish_instructor_created_idx');
        });

        Schema::table('course_reviews', function (Blueprint $table) {
            $table->index(['course_id', 'is_approved', 'created_at'], 'course_reviews_course_approved_created_idx');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->index(['guest_token', 'course_id'], 'carts_guest_course_idx');
            $table->index(['guest_token', 'created_at'], 'carts_guest_created_idx');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->index(['status', 'paid_at'], 'orders_status_paid_idx');
            $table->index(['instructor_id', 'status', 'paid_at'], 'orders_instructor_status_paid_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_instructor_status_paid_idx');
            $table->dropIndex('orders_status_paid_idx');
        });

        Schema::table('carts', function (Blueprint $table) {
            $table->dropIndex('carts_guest_created_idx');
            $table->dropIndex('carts_guest_course_idx');
        });

        Schema::table('course_reviews', function (Blueprint $table) {
            $table->dropIndex('course_reviews_course_approved_created_idx');
        });

        Schema::table('courses', function (Blueprint $table) {
            $table->dropIndex('courses_publish_instructor_created_idx');
            $table->dropIndex('courses_publish_category_created_idx');
        });
    }
};
