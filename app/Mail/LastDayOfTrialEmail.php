<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LastDayOfTrialEmail extends Mailable
{
    use SerializesModels;
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $company;
    public $subject; 

    public function __construct($company)
    {
        $this->company = (object) $company;
        $this->subject = 'Final Day of Your StaffViz Trial!';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.LastDayOfTrial')->subject($this->subject);
    }
}
