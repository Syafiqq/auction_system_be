<?php

namespace App\Presentation\Mail;

use App\Presentation\Mail\Presenter\BidPlacedMailPresenter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BidPlacedMailer extends Mailable
{
    use Queueable, SerializesModels;

    private BidPlacedMailPresenter $presenter;

    /**
     * Create a new message instance.
     * @param BidPlacedMailPresenter $presenter
     */
    public function __construct(BidPlacedMailPresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Bid Placed',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.auction.bid_placed',
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
