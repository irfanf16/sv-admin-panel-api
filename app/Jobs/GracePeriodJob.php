<?php

namespace App\Jobs;
 
use App\Models\Company;
use App\Mail\GracePeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class GracePeriodJob implements ShouldQueue
{
    protected $company;
    protected $user; 
 
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    public function __construct($company, $user)
    {
        $this->company = (object) $company;
        $this->user = (object) $user;
    }
 
    public function handle(): void
    {
        try {
            Mail::to($this->company->email)->send(new GracePeriod($this->company, $this->user));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}