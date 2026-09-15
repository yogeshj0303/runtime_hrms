<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\EmployeeLetter;

class EmployeeLetterMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $letter;
    public $subjectLine;

    /**
     * Create a new message instance.
     */
    public function __construct(EmployeeLetter $letter)
    {
        $this->letter = $letter;
        $this->subjectLine = $letter->template->title ?? 'Important Letter from HR';
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subjectLine,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.employee_letter',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
