<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rewrite_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->foreignId('website_page_id')->constrained()->cascadeOnDelete();
            $table->foreignId('ai_model_id')->nullable()->constrained('ai_models')->nullOnDelete();
            $table->foreignId('prompt_version_id')->nullable()->constrained('prompt_versions')->nullOnDelete();
            $table->string('trigger_type'); // scheduled, manual, retry, regenerate
            $table->string('status')->default('scheduled'); // job state machine
            $table->string('original_commit_hash', 40)->nullable();
            $table->string('original_content_hash', 64)->nullable();
            $table->string('rewritten_content_hash', 64)->nullable();
            $table->unsignedInteger('original_word_count')->nullable();
            $table->unsignedInteger('new_word_count')->nullable();
            $table->string('validation_status')->default('pending'); // pending, passed, failed, skipped
            $table->string('commit_hash', 40)->nullable();
            $table->string('workspace_path')->nullable();
            $table->string('queue_job_id')->nullable();
            $table->text('failure_reason')->nullable();
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->unsignedInteger('duration_seconds')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['website_id', 'status']);
            $table->index(['website_page_id', 'status']);
            $table->index(['status', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rewrite_jobs');
    }
};
