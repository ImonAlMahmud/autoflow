<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TestSmtpMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public array $configData = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '✨ [Autoflow] SMTP Outgoing Gateway Connection Test',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.test-mail',
            with: [
                'host'       => $this->configData['host'] ?? 'Configured',
                'port'       => $this->configData['port'] ?? '587',
                'encryption' => $this->configData['encryption'] ?? 'TLS',
            ],
        );
    }
}
