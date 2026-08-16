<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prompt_templates', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('language')->default('en');
            $table->string('status')->default('active'); // active, inactive
            $table->timestamps();
        });

        Schema::create('prompt_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('prompt_template_id')->constrained()->cascadeOnDelete();
            $table->unsignedInteger('version')->default(1);
            $table->text('system_prompt');
            $table->text('instructions');
            $table->decimal('temperature', 3, 2)->nullable();
            $table->boolean('is_current')->default(false);
            $table->timestamps();

            $table->index(['prompt_template_id', 'is_current']);
            $table->unique(['prompt_template_id', 'version']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prompt_versions');
        Schema::dropIfExists('prompt_templates');
    }
};
