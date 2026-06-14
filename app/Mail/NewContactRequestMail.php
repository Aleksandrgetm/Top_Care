<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewContactRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public array $data)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Jauns pieteikums no mājaslapas',
            replyTo: $this->data['email']
                ? [new Address($this->data['email'], $this->data['name'] ?? null)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.contact.admin',
            with: [
                'data' => $this->data,
            ],
        );
    }
}
