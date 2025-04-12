<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
// use Illuminate\Foundation\Bus\Dispatchable; 
// use Illuminate\Queue\InteractsWithQueue; 
// use Illuminate\Contracts\Queue\ShouldQueue;
// class AdminNotificationForCompanyCreate extends Mailable implements ShouldQueue
class InviteOwner extends Mailable
{
    use Queueable, SerializesModels;
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $user;
    public $custom_message;
    public $emailBody;
    public $subject;
    public function __construct($user, $message = '')
    {
        $this->user = $user;
        $this->custom_message = $message;
        $this->subject = 'StaffViz - Invite User';
        $this->emailBody = view('emails.emailBodyInviteOwner', compact('user'))->render();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        
        return $this->view('emails.genericEmail')->subject('Your Staffviz Company is One-Click Away');
    }
}
