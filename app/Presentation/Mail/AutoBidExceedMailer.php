<?php

namespace App\Presentation\Mail;

use App\Presentation\Mail\Presenter\AutoBidExceedMailPresenter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AutoBidExceedMailer extends Mailable
{
    use Queueable, SerializesModels;

    private AutoBidExceedMailPresenter $presenter;

    /**
     * Create a new message instance.
     * @param AutoBidExceedMailPresenter $presenter
     */
    public function __construct(AutoBidExceedMailPresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Autobid Warning',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.auction.autobid_exceed_amount',
            with: [
                'presenter' => $this->presenter,
            ],
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
