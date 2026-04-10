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
        Schema::create('ai_message_citations', function (Blueprint $table) {
            $table->id();

            $table->foreignId('message_id')->constrained('ai_chat_messages')->cascadeOnDelete();
            $table->foreignId('document_id')->constrained('ai_documents')->cascadeOnDelete();
            $table->foreignId('chunk_id')->constrained('ai_document_chunks')->cascadeOnDelete();

            $table->unsignedInteger('rank')->default(1);
            $table->decimal('score', 8, 4)->nullable();
            $table->text('snippet')->nullable();

            $table->timestamps();

            $table->index(['message_id']);
            $table->index(['document_id', 'chunk_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_message_citations');
    }
};
