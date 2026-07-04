<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PendaftaranPenelitiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pendaftaran;

    /**
     * Create a new message instance.
     */
    public function __construct($pendaftaran)
    {
        $this->pendaftaran = $pendaftaran;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Peneliti Baru - KEBUN RAYA SAMBAS',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pendaftaran-peneliti',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
