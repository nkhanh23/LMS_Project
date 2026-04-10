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
        Schema::create('gemini_settings', function (Blueprint $table) {
            $table->id();

            $table->text('api_key')->nullable();
            $table->string('model_name')->default('gemini-1.5-flash');
            $table->unsignedInteger('timeout_seconds')->default(30);
            $table->decimal('temperature', 3, 2)->default(0.20);
            $table->unsignedInteger('max_output_tokens')->default(1024);
            $table->boolean('is_enabled')->default(true);

            $table->unsignedBigInteger('updated_by')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gemini_settings');
    }
};
