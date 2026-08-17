<?php

namespace App\Mail;

use App\Models\RewriteJob;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobFailedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public RewriteJob $job,
        public string $errorMessage = 'Unknown error occurred.',
        public array $data = []
    ) {}

    public function envelope(): Envelope
    {
        $websiteName = $this->job->website?->name ?? 'Website';
        $pagePath    = $this->job->page?->path ?? 'Page';

        return new Envelope(
            subject: "⚠️ [Autoflow Alert] Automation Failed: {$websiteName} ({$pagePath})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-failed',
            with: [
                'websiteName'  => $this->job->website?->name ?? 'Website',
                'pagePath'     => $this->job->page?->path ?? '/index.html',
                'jobId'        => $this->job->id,
                'errorMessage' => $this->errorMessage,
                'actionUrl'    => url('/jobs'),
            ],
        );
    }
}
