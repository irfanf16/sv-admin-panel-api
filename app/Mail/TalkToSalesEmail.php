<?php

namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
class TalkToSalesEmail extends Mailable
{
    use Queueable, SerializesModels;
    // use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $talkToSales;

    public function __construct($talkToSales)
    {
        $this->talkToSales = $talkToSales;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // $this->talkToSales->email_sent = $this->talkToSales->email_sent + 1;
        // $this->talkToSales->last_email_time = date('Y-m-d H:i:s');
        // $this->talkToSales->save();
        return $this->view('emails.talk_to_sales')->subject('Talk to Sales');
    }
}
