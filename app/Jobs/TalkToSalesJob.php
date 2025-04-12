<?php

namespace App\Jobs;
 
use App\Models\TalkToSales;
use App\Mail\TalkToSalesEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class TalkToSalesJob implements ShouldQueue
{
    protected $talkToSales;
 
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    public function __construct(TalkToSales $talkToSales)
    {
        $this->talkToSales = $talkToSales;
    }
 
    public function handle(): void
    {
        $status = Mail::to(getenv('SUPPORT_STAFFVIZ_EMAIL'))->send(new TalkToSalesEmail($this->talkToSales));
        \Log::info('email Status = '. gettype($status));
    }
}