<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->boolean('notify_new_courses')->default(true);
            $table->boolean('notify_learning_reminders')->default(true);
            $table->boolean('notify_quiz_discussion_messages')->default(true);
            $table->timestamp('account_deletion_requested_at')->nullable();
            $table->text('account_deletion_reason')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_settings');
    }
};
