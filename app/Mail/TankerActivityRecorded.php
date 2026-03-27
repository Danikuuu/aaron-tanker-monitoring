<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TankerActivityRecorded extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $activityType,
        public string $tankerNumber,
        public string $recordedDate,
        public string $recordedBy,
        public array $fuelBreakdown = []
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Tanker ' . ucfirst($this->activityType) . ' Recorded',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.tanker-activity-recorded',
        );
    }
}
