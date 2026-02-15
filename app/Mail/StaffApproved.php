<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffApproved extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $staff) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Account Has Been Approved',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-approved',
        );
    }
}