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
        Schema::table('content_reports', function (Blueprint $table) {
            $table->foreignId('policy_id')->nullable()->after('lecture_id')->constrained('moderation_policies')->nullOnDelete();
            $table->foreignId('action_template_id')->nullable()->after('policy_id')->constrained('moderation_action_templates')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('content_reports', function (Blueprint $table) {
            $table->dropForeign(['policy_id']);
            $table->dropColumn('policy_id');
            $table->dropForeign(['action_template_id']);
            $table->dropColumn('action_template_id');
        });
    }
};
