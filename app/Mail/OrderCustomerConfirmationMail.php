<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderCustomerConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pasūtījums #' . $this->order->id . ' ir saņemts',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.customer',
            with: [
                'order' => $this->order,
            ],
        );
    }
}
