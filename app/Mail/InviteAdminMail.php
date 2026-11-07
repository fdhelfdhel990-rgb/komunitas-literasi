<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class InviteAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public $nama;
    public $username;
    public $passwordPlain;

    /**
     * Create a new message instance.
     */
    public function __construct($nama, $username, $passwordPlain)
    {
        $this->nama = $nama;
        $this->username = $username;
        $this->passwordPlain = $passwordPlain;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Undangan Admin Komunitas Literasi Tamsel',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.invite',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
