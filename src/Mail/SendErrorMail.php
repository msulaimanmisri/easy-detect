<?php

declare(strict_types=1);

namespace SulaimanMisri\EasyDetect\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendErrorMail extends Mailable implements ShouldQueue, ShouldBeUnique
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        private readonly string $errorMessage,
        private readonly string $errorFile,
        private readonly int $errorLine,
        private readonly string $errorTrace
    ) {}

    /**
     * Get the envelope information for the message.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: config('easy-detect.subject'),
        );
    }

    /**
     * Get the content of the mail message.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'easy-detect::mail.send-error-mail',
            with: [
                'message' => $this->errorMessage,
                'file' => $this->errorFile,
                'line' => $this->errorLine,
                'trace' => $this->errorTrace,
            ],
        );
    }

    /**
     * Get the attachments for the mail message.
     */
    public function attachments(): array
    {
        return [];
    }

    /**
     * Get the unique ID for the job.
     */
    public function uniqueId()
    {
        return md5($this->errorMessage . $this->errorFile . $this->errorLine);
    }

    /**
     * The number of seconds to wait before retrying the job.
     */
    public function uniqueFor()
    {
        return 300;
    }
}
