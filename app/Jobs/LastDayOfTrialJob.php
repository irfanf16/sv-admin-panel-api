<?php

namespace App\Jobs;
use App\Mail\LastDayOfTrialEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class LastDayOfTrialJob implements ShouldQueue
{
    protected $company;
 
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    public function __construct($company)
    {
        $this->company = (object) $company;
    }
 
    public function handle(): void
    {
        try {
            Mail::to($this->company->email)->send(new LastDayOfTrialEmail($this->company));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}