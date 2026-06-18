<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_knowledge_documents', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('doc_type', 50)->default('feature_how_to');
            $table->string('status', 20)->default('draft');
            $table->string('source_type', 20)->default('manual');
            $table->string('file_name')->nullable();
            $table->string('storage_disk')->nullable();
            $table->string('storage_path')->nullable();
            $table->longText('content_markdown');
            $table->text('excerpt')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['doc_type', 'status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_knowledge_documents');
    }
};
