<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('domain');
            $table->string('git_repository_url');
            $table->string('git_branch')->default('main');
            $table->string('git_auth_method')->default('https_token'); // https_token, ssh
            $table->text('git_access_token')->nullable(); // encrypted
            $table->text('git_ssh_key_path')->nullable(); // encrypted path
            $table->string('local_production_path')->nullable();
            $table->foreignId('default_ai_model_id')->nullable()->constrained('ai_models')->nullOnDelete();
            $table->foreignId('default_prompt_version_id')->nullable()->constrained('prompt_versions')->nullOnDelete();
            $table->unsignedInteger('default_rewrite_interval_days')->default(30);
            $table->string('language')->default('en');
            $table->string('timezone')->default('UTC');
            $table->boolean('auto_push_enabled')->default(true);
            $table->string('approval_mode')->default('automatic'); // automatic, manual
            $table->string('status')->default('active'); // active, paused, disabled
            $table->json('global_exclusion_selectors')->nullable();
            $table->json('protected_terms')->nullable();
            $table->timestamp('last_synced_at')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->index(['user_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
