<?php

namespace App\Presentation\Mail;

use App\Presentation\Mail\Presenter\AuctionWinnerMailPresenter;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AuctionWinnerMailer extends Mailable
{
    use Queueable, SerializesModels;

    private AuctionWinnerMailPresenter $presenter;

    /**
     * Create a new message instance.
     * @param AuctionWinnerMailPresenter $presenter
     */
    public function __construct(AuctionWinnerMailPresenter $presenter)
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
            html: 'mail.auction.auction_win',
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
