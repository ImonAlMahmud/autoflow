<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_models', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_provider_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // Display name
            $table->string('model_id'); // Actual model identifier e.g. qwen2.5:7b
            $table->decimal('temperature', 3, 2)->default(0.70);
            $table->integer('context_length')->nullable();
            $table->integer('max_output_tokens')->nullable();
            $table->integer('timeout_seconds')->default(120);
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();

            $table->index(['ai_provider_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_models');
    }
};
