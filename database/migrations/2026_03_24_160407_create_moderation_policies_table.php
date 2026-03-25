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
        Schema::create('moderation_policies', function (Blueprint $table) {
            $table->id();
            // policy code
            $table->string('code')->unique();
            // policy name
            $table->string('name');
            // policy target type
            $table->string('target_type')->nullable(); // course, instructor, content_report
            // policy description
            $table->text('description')->nullable();
            // policy is active
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('moderation_policies');
    }
};
