<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewOrderAdminMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Order $order)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address('noreply@topcare.lv', 'Top Care Group'),
            subject: 'Jauns pasūtījums ' . $this->order->display_order_number,
            replyTo: $this->order->customer_email
                ? [new Address($this->order->customer_email, $this->order->customer_name)]
                : [],
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.orders.admin',
            with: [
                'order' => $this->order,
            ],
        );
    }
}
