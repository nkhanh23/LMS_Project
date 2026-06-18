<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('ai_chat_sessions', function (Blueprint $table) {
            $table->string('mode')->default('lesson')->after('user_id');
            $table->string('scope')->default('lesson_context')->after('mode');
            $table->timestamp('closed_at')->nullable()->after('last_activity_at');
        });

        DB::statement('ALTER TABLE ai_chat_sessions ALTER COLUMN course_id DROP NOT NULL');
        DB::statement('ALTER TABLE ai_chat_sessions ALTER COLUMN lecture_id DROP NOT NULL');

        Schema::table('ai_chat_sessions', function (Blueprint $table) {
            $table->index(['user_id', 'mode', 'status'], 'ai_chat_sessions_user_mode_status_idx');
            $table->index(['user_id', 'mode', 'course_id', 'lecture_id', 'status'], 'ai_chat_sessions_mode_context_status_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ai_chat_sessions', function (Blueprint $table) {
            $table->dropIndex('ai_chat_sessions_user_mode_status_idx');
            $table->dropIndex('ai_chat_sessions_mode_context_status_idx');
            $table->dropColumn(['mode', 'scope', 'closed_at']);
        });

        DB::statement('ALTER TABLE ai_chat_sessions ALTER COLUMN course_id SET NOT NULL');
        DB::statement('ALTER TABLE ai_chat_sessions ALTER COLUMN lecture_id SET NOT NULL');
    }
};
