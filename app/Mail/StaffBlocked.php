<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffBlocked extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $staff) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Has Been Blocked',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-blocked',
        );
    }
}