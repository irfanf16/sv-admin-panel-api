<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DatabaseCreationFailedNotification extends Mailable
{
    use Queueable, SerializesModels;
    public $username;
    public $email;
    public $reference_number;
    public $company_title;
    public $reason;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($username, $email, $reference_number, $company_title, $reason)
    {
        $this->username = $username;
        $this->email = $email;
        $this->reference_number = $reference_number;
        $this->company_title = $company_title;
        $this->reason = $reason;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.DatabaseCreationFailedNotification')->subject('Company Account Setup Failed');
    }
}
