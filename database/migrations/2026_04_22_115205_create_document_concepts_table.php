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
        Schema::create('document_concepts', function (Blueprint $table) {

            $table->id();
            $table->unsignedBigInteger('document_id');
            $table->unsignedBigInteger('concept_id');
            $table->timestamps();

            $table->foreign('document_id')
                ->references('id')
                ->on('ai_documents')
                ->onDelete('cascade');

            $table->foreign('concept_id')
                ->references('id')
                ->on('concepts')
                ->onDelete('cascade');

            $table->unique(['document_id', 'concept_id']);
            $table->index('document_id');
            $table->index('concept_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_concepts');
    }
};
