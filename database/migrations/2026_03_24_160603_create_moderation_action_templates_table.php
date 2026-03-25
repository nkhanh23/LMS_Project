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
        Schema::create('moderation_action_templates', function (Blueprint $table) {
            $table->id();
            // action code
            $table->string('code')->unique();
            // action name
            $table->string('name');
            // action target type
            $table->string('target_type')->nullable();
            // action default note
            $table->text('default_note')->nullable();
            // action requires reason
            $table->boolean('requires_reason')->default(true);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_action_templates');
    }
};
