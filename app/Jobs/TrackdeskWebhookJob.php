<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TrackdeskService;

class TrackdeskWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;
    protected $trackdeskWebhookService; 
    /**
     * Create a new job instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->trackdeskWebhookService = new TrackdeskService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if(isset($this->data["affiliateCreated"]) && is_array($this->data["affiliateCreated"])) {
            $this->trackdeskWebhookService->saveAffiliate($this->data); 
        }
    }
}
