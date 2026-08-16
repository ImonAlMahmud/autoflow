<?php

namespace App\Livewire\Settings;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Index extends Component
{
    public string $appName = 'Autoflow';
    public string $appTimezone = 'UTC';

    // SMTP Settings (Nullable strings)
    public ?string $mailHost = '';
    public ?string $mailPort = '587';
    public ?string $mailUsername = '';
    public ?string $mailPassword = '';
    public ?string $mailEncryption = 'tls';
    public ?string $mailFromAddress = '';
    public ?string $mailFromName = '';

    // Notification Event Toggles
    public bool $notifyOnRewriteComplete = true;
    public bool $notifyOnApprovalRequired = true;
    public bool $notifyOnJobFailed = true;
    public bool $notifyOnGitPushFailed = true;

    public ?string $testEmailRecipient = '';

    public function mount()
    {
        $this->appName = SystemSetting::get('app_name', 'Autoflow') ?? 'Autoflow';
        $this->appTimezone = SystemSetting::get('app_timezone', 'UTC') ?? 'UTC';

        $this->mailHost = SystemSetting::get('mail_host', config('mail.mailers.smtp.host')) ?? '';
        $this->mailPort = (string)(SystemSetting::get('mail_port', config('mail.mailers.smtp.port')) ?? '587');
        $this->mailUsername = SystemSetting::get('mail_username', config('mail.mailers.smtp.username')) ?? '';
        $this->mailPassword = SystemSetting::get('mail_password', config('mail.mailers.smtp.password')) ?? '';
        $this->mailEncryption = SystemSetting::get('mail_encryption', config('mail.mailers.smtp.encryption')) ?? 'tls';
        $this->mailFromAddress = SystemSetting::get('mail_from_address', config('mail.from.address')) ?? '';
        $this->mailFromName = SystemSetting::get('mail_from_name', config('mail.from.name')) ?? 'Autoflow Alerts';

        $this->notifyOnRewriteComplete = (bool) SystemSetting::get('notify_rewrite_complete', true);
        $this->notifyOnApprovalRequired = (bool) SystemSetting::get('notify_approval_required', true);
        $this->notifyOnJobFailed = (bool) SystemSetting::get('notify_job_failed', true);
        $this->notifyOnGitPushFailed = (bool) SystemSetting::get('notify_git_failed', true);
    }

    public function saveSettings()
    {
        SystemSetting::set('app_name', $this->appName ?? 'Autoflow', 'general');
        SystemSetting::set('app_timezone', $this->appTimezone ?? 'UTC', 'general');

        SystemSetting::set('mail_host', $this->mailHost ?? '', 'smtp');
        SystemSetting::set('mail_port', $this->mailPort ?? '587', 'smtp');
        SystemSetting::set('mail_username', $this->mailUsername ?? '', 'smtp');
        SystemSetting::set('mail_password', $this->mailPassword ?? '', 'smtp', 'string', true);
        SystemSetting::set('mail_encryption', $this->mailEncryption ?? 'tls', 'smtp');
        SystemSetting::set('mail_from_address', $this->mailFromAddress ?? '', 'smtp');
        SystemSetting::set('mail_from_name', $this->mailFromName ?? 'Autoflow Alerts', 'smtp');

        SystemSetting::set('notify_rewrite_complete', $this->notifyOnRewriteComplete ? '1' : '0', 'notifications', 'boolean');
        SystemSetting::set('notify_approval_required', $this->notifyOnApprovalRequired ? '1' : '0', 'notifications', 'boolean');
        SystemSetting::set('notify_job_failed', $this->notifyOnJobFailed ? '1' : '0', 'notifications', 'boolean');
        SystemSetting::set('notify_git_failed', $this->notifyOnGitPushFailed ? '1' : '0', 'notifications', 'boolean');

        $this->dispatch('toast', title: 'Settings Saved', message: 'SMTP and Email notification triggers updated successfully.', type: 'success');
    }

    public function sendTestEmail()
    {
        if (empty($this->testEmailRecipient)) {
            $this->dispatch('toast', title: 'Email Required', message: 'Please enter a test email recipient address.', type: 'warning');
            return;
        }

        try {
            config([
                'mail.mailers.smtp.host' => $this->mailHost,
                'mail.mailers.smtp.port' => $this->mailPort,
                'mail.mailers.smtp.username' => $this->mailUsername,
                'mail.mailers.smtp.password' => $this->mailPassword,
                'mail.mailers.smtp.encryption' => $this->mailEncryption,
                'mail.from.address' => $this->mailFromAddress,
                'mail.from.name' => $this->mailFromName,
            ]);

            Mail::raw("Hello! This is a test email sent from your Autoflow AI Content Automation System.\n\nYour SMTP settings are configured correctly!", function ($message) {
                $message->to($this->testEmailRecipient)
                        ->subject('Autoflow SMTP Connection Test');
            });

            $this->dispatch('toast', title: 'Test Email Sent', message: "Test message successfully dispatched to {$this->testEmailRecipient}.", type: 'success');
        } catch (\Exception $e) {
            $this->dispatch('toast', title: 'SMTP Error', message: $e->getMessage(), type: 'danger');
        }
    }

    public function render()
    {
        return view('livewire.settings.index');
    }
}
