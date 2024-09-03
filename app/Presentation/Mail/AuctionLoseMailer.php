<?php

namespace App\Presentation\Mail;

use App\Presentation\Mail\Presenter\AuctionLoseMailPresenter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuctionLoseMailer extends Mailable
{
    use Queueable, SerializesModels;

    private AuctionLoseMailPresenter $presenter;

    /**
     * Create a new message instance.
     * @param AuctionLoseMailPresenter $presenter
     */
    public function __construct(AuctionLoseMailPresenter $presenter)
    {
        $this->presenter = $presenter;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Bid Result',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            html: 'mail.auction.auction_lose',
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
