<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ContactAutoReplyMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Paldies par pieteikumu | Top Care Group',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.auto-reply',
            with: [
                'data' => $this->data,
            ],
        );
    }
}
