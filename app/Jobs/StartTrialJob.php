<?php

namespace App\Jobs;
 
use App\Models\Company;
use App\Models\UserTableMaster;
use App\Mail\StartTrialEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
class StartTrialJob implements ShouldQueue
{
    protected $company;
    protected $user;
 
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
 
    public function __construct(Company $company, UserTableMaster $user)
    {
        $this->company = $company;
        $this->user = $user;
    }
 
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(new StartTrialEmail($this->company, $this->user));
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}