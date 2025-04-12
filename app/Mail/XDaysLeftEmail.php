<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class XDaysLeftEmail extends Mailable
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
        $this->subject = 'Halfway Through Your StaffViz Trial - '.$this->company->today_diff_in_days.' Days Left!';
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.XDaysLeft')->subject($this->subject);
    }
}
