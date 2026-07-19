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

    public function __construct($pendaftaran)
    {
        $this->pendaftaran = $pendaftaran;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pendaftaran Peneliti Baru - KEBUN RAYA SAMBAS',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pendaftaran-peneliti',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
