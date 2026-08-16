<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('git_operations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('website_id')->constrained()->cascadeOnDelete();
            $table->foreignId('rewrite_job_id')->nullable()->constrained()->nullOnDelete();
            $table->string('operation'); // clone, pull, fetch, commit, push, status
            $table->string('status'); // success, failed
            $table->string('commit_hash', 40)->nullable();
            $table->string('branch')->nullable();
            $table->text('message')->nullable();
            $table->text('error')->nullable();
            $table->unsignedInteger('duration_ms')->nullable();
            $table->timestamps();

            $table->index(['website_id', 'operation']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('git_operations');
    }
};
