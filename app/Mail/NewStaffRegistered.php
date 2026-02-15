<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewStaffRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $staff) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Staff Registration Pending Approval',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-staff-registered',
        );
    }
}