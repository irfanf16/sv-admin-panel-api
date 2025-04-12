<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeWebhookService;
use App\Jobs\StripWebhookJob;
class StripWebhookController extends Controller
{
    private $stripeWebhookService = null;
    public function __construct() {
        $this->stripeWebhookService = new StripeWebhookService();
    }
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
        // dd(config('stripe.private_key'));
        // dispatch(new StripWebhookJob($request->toArray()));
        // $invoice = $this->stripeWebhookService->updateSubscription($request->toArray());
        // dd($invoice);

        try {
            $event = $this->stripeWebhookService->isValidRequest();
            \Log::channel('stripe_webhook')->info('Stripe Webhook Data::'. json_encode($event));
            if($event == false) {
                return response()->json([
                    'message' => 'Invalid Request',
                ], 500);
            }
            $stripeEventData = $event->toArray();
            if(in_array($event->type, [
                'customer.subscription.created',
                'customer.subscription.deleted',
                'customer.subscription.updated',
                'customer.subscription.paused',
                'customer.subscription.resumed',
                'customer.subscription.pending_update_applied',
                'customer.subscription.pending_update_expired',
                'customer.subscription.trial_will_end',
            ])) {
                dispatch(new StripWebhookJob($stripeEventData));
                // $udpated = $this->stripeWebhookService->updateSubscription($stripeEventData);
            }

            if(in_array($event->type, [
                'invoice.will_be_due',
                'invoice.voided',
                'invoice.upcoming',
                'invoice.updated',
                'invoice.sent',
                'invoice.payment_succeeded',
                'invoice.payment_failed',
                'invoice.payment_action_required',
                'invoice.paid', 'invoice.overdue',
                'invoice.marked_uncollectible',
                'invoice.finalized',
                'invoice.finalization_failed',
                'invoice.deleted',
                'invoice.created',
                ])) {
                // Stripe sometimes send the invoice data first. So we are delaying our job just to make sure Subscription table has updated successfully.
                dispatch(new StripWebhookJob($stripeEventData))->delay(now()->addSeconds(5));
                // $invoice = $this->stripeWebhookService->invoice($event->toArray());
            }

        } catch (\Exception $ex) {
            \Log::channel('stripe_webhook')->error('Hook data is invalid. Error Message:: '. $ex->getMessage());
            return response()->json([
                'message' => 'Invalid Request',
            ], 500);
        }

        return response()->json([
            'event' => $event,
        ]);
    }
    public function alive() {
        echo 'I am alive';
    }
}
