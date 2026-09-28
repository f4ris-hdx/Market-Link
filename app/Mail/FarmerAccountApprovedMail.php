<?php

namespace App\Mail;

use App\Models\Farmer;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class FarmerAccountApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Farmer $farmer) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your farmer account has been approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.farmers.approved',
            with: ['farmer' => $this->farmer],
        );
    }
}
