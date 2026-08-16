<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->string('git_author_name')->default('Imon Mahmud')->after('git_access_token');
            $table->string('git_author_email')->default('imon.mahmud4@gmail.com')->after('git_author_name');
        });
    }

    public function down(): void
    {
        Schema::table('websites', function (Blueprint $table) {
            $table->dropColumn(['git_author_name', 'git_author_email']);
        });
    }
};
