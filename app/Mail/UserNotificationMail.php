<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $event;
    protected $user;
    protected $course;
    /**
     * Create a new message instance.
     */
    public function __construct($event, $user, $course)
    {
        $this->event = $event;
        $this->user = $user;
        $this->course = $course;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: ($this->event == 'course_start') ? 'Action Required: Start the Course on StaffViz Tracker ' : 'Course Stop',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {

        return new Content(
            view: ($this->event == 'course_start') ? 'emails.start_course_tracking' : 'emails.stop_course_tracking',
            with: [ 
                    'event' => $this->event, 
                    'user' => $this->user, 
                    'course' => $this->course, 
                    'subject' => $this->subject
                ]
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
