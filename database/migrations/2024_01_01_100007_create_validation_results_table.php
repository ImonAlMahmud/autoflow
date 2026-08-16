<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('validation_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rewrite_job_id')->constrained()->cascadeOnDelete();
            $table->boolean('json_validity')->default(false);
            $table->boolean('segment_completeness')->default(false);
            $table->boolean('protected_values')->default(false);
            $table->boolean('html_structure')->default(false);
            $table->boolean('links_preserved')->default(false);
            $table->boolean('word_count')->default(false);
            $table->boolean('language_check')->default(false);
            $table->boolean('content_quality')->default(false);
            $table->boolean('overall_passed')->default(false);
            $table->json('details')->nullable(); // per-check detailed results
            $table->timestamps();

            $table->unique('rewrite_job_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('validation_results');
    }
};
