<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WelcomeMail extends Mailable
{
    public function __construct(
        public User $user,
        public string $verificationCode,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('app.name'),
            ),
            subject: 'Welcome to '.config('app.name'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
