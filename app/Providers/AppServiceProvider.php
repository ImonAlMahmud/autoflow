<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        date_default_timezone_set('Asia/Dhaka');
        config(['app.timezone' => 'Asia/Dhaka']);

        try {
            if (\Illuminate\Support\Facades\Schema::hasTable('system_settings')) {
                $mailHost = \App\Models\SystemSetting::get('mail_host');
                if ($mailHost) {
                    $encryption = \App\Models\SystemSetting::get('mail_encryption', 'tls');
                    $encryption = ($encryption === 'null' || empty($encryption)) ? null : strtolower($encryption);

                    config([
                        'mail.default' => 'smtp',
                        'mail.mailers.smtp.transport' => 'smtp',
                        'mail.mailers.smtp.host' => $mailHost,
                        'mail.mailers.smtp.port' => (int) (\App\Models\SystemSetting::get('mail_port', 587)),
                        'mail.mailers.smtp.username' => \App\Models\SystemSetting::get('mail_username'),
                        'mail.mailers.smtp.password' => \App\Models\SystemSetting::get('mail_password'),
                        'mail.mailers.smtp.encryption' => $encryption,
                        'mail.from.address' => \App\Models\SystemSetting::get('mail_from_address') ?: \App\Models\SystemSetting::get('mail_username'),
                        'mail.from.name' => \App\Models\SystemSetting::get('mail_from_name', 'Autoflow'),
                    ]);
                }
            }
        } catch (\Throwable $e) {
            // Ignore if DB is not connected yet during early boot/migrations
        }
    }
}
