<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\StripeWebhookService;

class StripWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event;
    protected $stripeWebhookService; 
    /**
     * Create a new job instance.
     */
    public function __construct($event)
    {
        $this->event = $event;
        $this->stripeWebhookService = new StripeWebhookService();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (str_contains($this->event['type'], 'customer.subscription.')) {
            $this->stripeWebhookService->updateSubscription($this->event); 
        }

        if (str_contains($this->event['type'], 'invoice.')) {
            $this->stripeWebhookService->invoice($this->event); 
        }
    }
}
