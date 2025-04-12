<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Foundation\Bus\Dispatchable; 
// use Illuminate\Queue\InteractsWithQueue; 
// use Illuminate\Contracts\Queue\ShouldQueue;
// class AdminNotificationForCompanyCreate extends Mailable implements ShouldQueue
class AdminNotificationForCompanyCreate extends Mailable
{
    use Queueable, SerializesModels;
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $company;

    public function __construct($user, $company)
    {
        $this->user = $user;
        $this->company = $company;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.AdminNotificationForCompanyCreate')->subject('New Company Created by '. $this->user->first_name . ' ' .$this->user->last_name);
    }
}
