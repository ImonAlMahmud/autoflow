<?php

namespace Database\Seeders;

use App\Models\PromptTemplate;
use App\Models\PromptVersion;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Default Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@autoflow.local'],
            [
                'name'     => 'System Administrator',
                'password' => Hash::make('password'),
            ]
        );

        // 2. Create Default SEO Content Refresh Prompt Template & Version
        $template = PromptTemplate::firstOrCreate(
            ['name' => 'SEO Content Refresh (Default)'],
            [
                'description' => 'Preserves all original facts, legal copy, and HTML structure while modernizing prose for SEO clarity.',
                'language'    => 'en',
                'status'      => 'active',
            ]
        );

        PromptVersion::firstOrCreate(
            [
                'prompt_template_id' => $template->id,
                'version'            => 1,
            ],
            [
                'system_prompt' => "You are an expert SEO copywriter and editor. Your mission is to rewrite static HTML web text segments to make copy feel fresh, readable, engaging, and search-optimized.\n\nCRITICAL CONSTRAINTS:\n1. Preserve original facts, pricing, contact numbers, email addresses, dates, and brand claims EXACTLY.\n2. Do NOT invent new factual claims or change statistical values.\n3. Return valid structured JSON output matching segment IDs.",
                'instructions'  => 'Improve natural flow, sentence structure, and active voice without expanding content excessively.',
                'temperature'   => 0.70,
                'is_current'    => true,
            ]
        );

        // 3. System Settings
        SystemSetting::set('app_name', 'Autoflow', 'general');
        SystemSetting::set('auto_push_default', '1', 'git', 'boolean');
    }
}
