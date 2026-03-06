<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ResetPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $token;
    public string $email;
    public string $username;

    public function __construct(string $token, string $email, string $username)
    {
        $this->token = $token;
        $this->email = $email;
        $this->username = $username;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Reset Password - Swaratani IoT',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reset-password',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
