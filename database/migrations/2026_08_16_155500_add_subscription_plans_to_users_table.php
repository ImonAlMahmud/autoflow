<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('plan')->default('starter')->after('email'); // starter, pro, enterprise
            $table->string('plan_status')->default('active')->after('plan'); // active, trialing, cancelled
            $table->unsignedInteger('websites_limit')->default(3)->after('plan_status');
            $table->unsignedInteger('monthly_rewrites_limit')->default(100)->after('websites_limit');
            $table->unsignedInteger('rewrites_used_this_month')->default(0)->after('monthly_rewrites_limit');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'plan',
                'plan_status',
                'websites_limit',
                'monthly_rewrites_limit',
                'rewrites_used_this_month',
            ]);
        });
    }
};
