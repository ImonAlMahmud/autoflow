<?php

namespace App\Mail;

use App\Models\RewriteJob;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class JobCompletedMail extends Mailable
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
            subject: "🚀 [Autoflow] Automation Completed: {$websiteName} ({$pagePath})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.job-completed',
            with: [
                'websiteName'   => $this->job->website?->name ?? 'Website',
                'pagePath'      => $this->job->page?->path ?? '/index.html',
                'segmentsCount' => $this->data['segments_count'] ?? null,
                'aiModel'       => $this->job->aiModel?->name ?? $this->data['ai_model'] ?? null,
                'executionTime' => $this->job->finished_at ? $this->job->finished_at->format('M d, Y - h:i A') : now()->format('M d, Y - h:i A'),
                'jobId'         => $this->job->id,
                'actionUrl'     => url('/jobs'),
            ],
        );
    }
}
