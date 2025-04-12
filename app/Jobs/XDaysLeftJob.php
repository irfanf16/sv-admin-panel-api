<?php

namespace App\Jobs;
 
use App\Models\Company;
use App\Mail\XDaysLeftEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class XDaysLeftJob implements ShouldQueue
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
            Mail::to($this->company->email)->send(new XDaysLeftEmail($this->company));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}