<?php

namespace App\Mail;

use App\Models\RewriteJob;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobPendingApprovalMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $job,
        public array $data = []
    ) {}

    public function envelope(): Envelope
    {
        $websiteName = $this->job->website?->name ?? 'Website';
        $pagePath    = $this->job->page?->path ?? 'Page';

        return new Envelope(
            subject: "🔍 [Autoflow] Approval Required: {$websiteName} ({$pagePath})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-pending-approval',
            with: [
                'websiteName' => $this->job->website?->name ?? 'Website',
                'pagePath'    => $this->job->page?->path ?? '/index.html',
                'jobId'       => $this->job->id,
                'actionUrl'   => url('/jobs'),
            ],
        );
    }
}
