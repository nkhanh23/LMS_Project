<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PayoutProcessedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $payout;
    /**
     * Create a new message instance.
     */
    public function __construct($payout)
    {
        $this->payout = $payout;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->payout->status === 'approved'
            ? 'Yêu cầu rút tiền của bạn đã được chuyển khoản'
            : 'Yêu cầu rút tiền của bạn bị từ chối';

        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.payout_processed',
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
