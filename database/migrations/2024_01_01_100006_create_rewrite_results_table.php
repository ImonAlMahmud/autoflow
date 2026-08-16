<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewrite_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rewrite_job_id')->constrained()->cascadeOnDelete();
            $table->json('original_segments'); // extracted content segments
            $table->json('rewritten_segments')->nullable(); // AI output segments
            $table->longText('diff_data')->nullable(); // computed diff
            $table->string('original_html_hash', 64)->nullable();
            $table->string('rewritten_html_hash', 64)->nullable();
            $table->unsignedInteger('ai_request_tokens')->nullable();
            $table->unsignedInteger('ai_response_tokens')->nullable();
            $table->unsignedInteger('ai_duration_ms')->nullable();
            $table->timestamps();

            $table->unique('rewrite_job_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewrite_results');
    }
};
