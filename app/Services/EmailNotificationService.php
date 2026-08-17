<?php

namespace App\Services;

use App\Mail\JobCompletedMail;
use App\Mail\JobFailedMail;
use App\Mail\JobPendingApprovalMail;
use App\Mail\TestSmtpMail;
use App\Models\RewriteJob;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EmailNotificationService
{
    /**
     * Configure runtime SMTP transport from Database settings.
     */
    public static function configureSmtp(): bool
    {
        try {
            $mailHost = SystemSetting::get('mail_host');
            if (empty($mailHost)) {
                return false;
            }

            $encryption = SystemSetting::get('mail_encryption', 'tls');
            $encryption = ($encryption === 'null' || empty($encryption)) ? null : strtolower($encryption);

            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.transport'  => 'smtp',
                'mail.mailers.smtp.host'       => $mailHost,
                'mail.mailers.smtp.port'       => (int) (SystemSetting::get('mail_port', 587)),
                'mail.mailers.smtp.username'   => SystemSetting::get('mail_username'),
                'mail.mailers.smtp.password'   => SystemSetting::get('mail_password'),
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.from.address'            => SystemSetting::get('mail_from_address') ?: SystemSetting::get('mail_username'),
                'mail.from.name'               => SystemSetting::get('mail_from_name', 'Autoflow'),
            ]);

            Mail::purge('smtp');
            return true;
        } catch (Throwable $e) {
            Log::warning("EmailNotificationService: Failed to configure SMTP: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Resolve the recipient email address for a given job.
     */
    public static function resolveRecipient(RewriteJob $job): ?string
    {
        $job->loadMissing(['website.user']);

        if (!empty($job->website?->notification_email)) {
            return trim($job->website->notification_email);
        }

        if (!empty($job->website?->user?->email)) {
            return trim($job->website->user->email);
        }

        $admin = User::where('role', 'superadmin')->orWhere('role', 'admin')->first();
        if ($admin && !empty($admin->email)) {
            return trim($admin->email);
        }

        $fromAddress = SystemSetting::get('mail_from_address');
        return !empty($fromAddress) ? trim($fromAddress) : null;
    }

    /**
     * Send email when a rewrite automation job completes successfully.
     */
    public static function notifyJobCompleted(RewriteJob $job, array $data = []): bool
    {
        if (!SystemSetting::get('notify_rewrite_complete', true)) {
            return false;
        }

        self::configureSmtp();
        $recipient = self::resolveRecipient($job);

        if (empty($recipient)) {
            Log::warning("EmailNotificationService: No recipient email found for job #{$job->id}.");
            return false;
        }

        try {
            Mail::to($recipient)->send(new JobCompletedMail($job, $data));
            Log::info("EmailNotificationService: Sent JobCompletedMail for job #{$job->id} to {$recipient}");
            return true;
        } catch (Throwable $e) {
            Log::error("EmailNotificationService: Failed to send JobCompletedMail for job #{$job->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email when a job pauses for manual approval.
     */
    public static function notifyPendingApproval(RewriteJob $job, array $data = []): bool
    {
        if (!SystemSetting::get('notify_approval_required', true)) {
            return false;
        }

        self::configureSmtp();
        $recipient = self::resolveRecipient($job);

        if (empty($recipient)) {
            Log::warning("EmailNotificationService: No recipient email found for job #{$job->id}.");
            return false;
        }

        try {
            Mail::to($recipient)->send(new JobPendingApprovalMail($job, $data));
            Log::info("EmailNotificationService: Sent JobPendingApprovalMail for job #{$job->id} to {$recipient}");
            return true;
        } catch (Throwable $e) {
            Log::error("EmailNotificationService: Failed to send JobPendingApprovalMail for job #{$job->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send email when a job encounters a failure or error.
     */
    public static function notifyJobFailed(RewriteJob $job, string $reason, array $data = []): bool
    {
        if (!SystemSetting::get('notify_job_failed', true)) {
            return false;
        }

        self::configureSmtp();
        $recipient = self::resolveRecipient($job);

        if (empty($recipient)) {
            Log::warning("EmailNotificationService: No recipient email found for job #{$job->id}.");
            return false;
        }

        try {
            Mail::to($recipient)->send(new JobFailedMail($job, $reason, $data));
            Log::info("EmailNotificationService: Sent JobFailedMail for job #{$job->id} to {$recipient}");
            return true;
        } catch (Throwable $e) {
            Log::error("EmailNotificationService: Failed to send JobFailedMail for job #{$job->id}: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send test SMTP verification email.
     */
    public static function sendTestEmail(string $recipient, array $runtimeConfig = []): void
    {
        if (!empty($runtimeConfig)) {
            $encryption = empty($runtimeConfig['encryption']) || $runtimeConfig['encryption'] === 'null' ? null : strtolower($runtimeConfig['encryption']);

            config([
                'mail.default'                 => 'smtp',
                'mail.mailers.smtp.transport'  => 'smtp',
                'mail.mailers.smtp.host'       => $runtimeConfig['host'] ?? '',
                'mail.mailers.smtp.port'       => (int) ($runtimeConfig['port'] ?? 587),
                'mail.mailers.smtp.username'   => $runtimeConfig['username'] ?? '',
                'mail.mailers.smtp.password'   => $runtimeConfig['password'] ?? '',
                'mail.mailers.smtp.encryption' => $encryption,
                'mail.from.address'            => $runtimeConfig['from_address'] ?: ($runtimeConfig['username'] ?? ''),
                'mail.from.name'               => $runtimeConfig['from_name'] ?: 'Autoflow',
            ]);

            Mail::purge('smtp');
        } else {
            self::configureSmtp();
        }

        Mail::to($recipient)->send(new TestSmtpMail($runtimeConfig));
    }
}
