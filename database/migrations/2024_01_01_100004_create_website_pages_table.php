<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('website_pages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->string('path'); // relative path, e.g. about.html
            $table->string('friendly_name')->nullable();
            $table->boolean('rewrite_enabled')->default(false);
            $table->unsignedInteger('rewrite_interval_days')->nullable(); // null = inherit from website
            $table->foreignId('ai_model_id')->nullable()->constrained('ai_models')->nullOnDelete();
            $table->foreignId('prompt_version_id')->nullable()->constrained('prompt_versions')->nullOnDelete();
            $table->json('rewrite_scope')->nullable(); // which element types to rewrite
            $table->json('protected_values')->nullable(); // custom protected terms
            $table->json('excluded_selectors')->nullable(); // CSS selectors to skip
            $table->string('approval_mode')->nullable(); // null = inherit from website
            $table->string('content_hash', 64)->nullable(); // SHA-256 of last known editable content
            $table->timestamp('last_rewrite_at')->nullable();
            $table->timestamp('next_rewrite_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['website_id', 'path']);
            $table->index(['website_id', 'rewrite_enabled']);
            $table->index(['rewrite_enabled', 'next_rewrite_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('website_pages');
    }
};
