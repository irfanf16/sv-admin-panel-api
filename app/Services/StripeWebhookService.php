<?php

namespace App\Services;

use App\Models\Company;
use App\Http\Controllers\SettingsAdminController;
use App\Models\Invoices;
use App\Libraries\Masterdb;
use App\Models\ModuleFeatureList;
use App\Models\Products;
use App\Models\Settings\Email;
use App\Models\User;
use App\Models\UserTableMaster;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\Subscriptions;
use App\Services\StripeService;
use Illuminate\Support\Facades\Log;

class StripeWebhookService
{

    private $stripeWebhook = null;
    private $limit = 100;
    private $endpoint_secrect = '';
    private $api_key = '';
    private $setting = null;

    public function __construct()
    {
        if (empty($this->api_key)) {
            $this->api_key = $this->getPrivateKey();
        }
        $this->stripeWebhook = new \Stripe\StripeClient([
            'api_key' => $this->api_key,
            'stripe_version' => '2023-10-16'
        ]);

        $this->endpoint_secrect = $this->getEndpointSecret();
    }

    public function getPrivateKey()
    {
        $api_key = config('stripe.private_key');
        return $api_key;
    }

    public function getEndpointSecret()
    {
        return config('stripe.endpoint_secret');
    }

    public function isValidRequest()
    {
        if (!isset($_SERVER['HTTP_STRIPE_SIGNATURE'])) {
            return false;
        }
        $payload = @file_get_contents('php://input');
        $sig_header = $_SERVER['HTTP_STRIPE_SIGNATURE'];
        $event = null;

        try {
            $event = \Stripe\Webhook::constructEvent(
                $payload, $sig_header, $this->endpoint_secrect
            );
        } catch (\UnexpectedValueException $e) {
            return false;
        } catch (\Stripe\Exception\SignatureVerificationException $e) {
            return false;
        }
        return $event;
    }

    public function updateSubscription($event)
    {
        Log::channel('stripe_webhook')->info('Inside StripWebhookService.php => updateSubscription() :: event = ' . json_encode($event));
        Masterdb::connect_master_db();
        $subscription = $event["data"]["object"];
        $status = $subscription['status'];
        $payment_status = !is_null($subscription['pause_collection']) ? $subscription['pause_collection']['behavior'] : null;
        // $company = Company::where('subscription_id', $subscription["id"])->first();
        $company = $this->getCompany($subscription["id"]);
        $user = User::where('id', $company->super_admin_id)->first();
        if (empty($company)) {
            Log::channel('stripe_webhook')->info('Company Not Found with Subscription Id=' . $subscription["id"]);
            return false;
        }

        if (isset($subscription['period_end'])) {
            $plan_expiry_stamp = $subscription['period_end'];
        }
        if (isset($subscription['current_period_end'])) {
            $plan_expiry_stamp = $subscription['current_period_end'];
        }

        $quantity = 0;
        foreach ($subscription['items']['data'] as $key => $item) {
            $quantity = $item['quantity'];
            break;
        }

        $plan_expiry = date('Y-m-d H:i:s', $plan_expiry_stamp);
        if (isset($subscription['subscription_details'])) {
            Log::channel('stripe_webhook')->info('I am in subscription_details');
            $plan_id = $subscription['subscription_details']['metadata']['plan_id'];
            $plan_price_id = isset($subscription['subscription_details']['metadata']['price_id']) ? $subscription['subscription_details']['metadata']['price_id'] : '';
            $module_features_list = explode(',', isset($subscription['subscription_details']['metadata']['module_features_list']) ? $subscription['subscription_details']['metadata']['module_features_list'] : "");
            $module_list = explode(',', isset($subscription['subscription_details']['metadata']['modules']) ? $subscription['subscription_details']['metadata']['modules'] : "");
        }

        if (isset($subscription['metadata'])) {
            Log::channel('stripe_webhook')->info('I am in subscription metadata');
            $plan_id = $subscription['metadata']['plan_id'];
            $plan_price_id = isset($subscription['metadata']['price_id']) ? $subscription['metadata']['price_id'] : '';
            $module_features_list = explode(',', isset($subscription['metadata']['module_features_list']) ? $subscription['metadata']['module_features_list'] : "");
            $module_list = explode(',', isset($subscription['metadata']['modules']) ? $subscription['metadata']['modules'] : "");
        }
        // Addons list fetch
        if(isset($subscription['metadata']['addons']) && !empty($subscription['metadata']['addons'])) {
            Log::channel('stripe_webhook')->info('Updating Addons features.....');
            $addsons = explode(',', $subscription['metadata']['addons']);
            $products = Products::wherein('stripe_id', $addsons)->get();
            if ($products->isEmpty()) {
                Log::channel('stripe_webhook')->info('Addons not found in the database.'. $subscription['metadata']['addons']);
            } else {
                foreach ($products as $key => $product) {
                    $addsonsList = explode(',', $product->product['metadata']['modules']);
                    $addsonsFeaturesList = explode(',', $product->product['metadata']['module_features_list']);
                    if(!empty($module_list)) {
                        $module_list = array_merge($addsonsList, $module_list);
                        $module_features_list = array_merge($addsonsFeaturesList, $module_features_list);
                    } else {
                        $module_list = $addsonsList;
                        $module_features_list = $addsonsFeaturesList;
                    }
                }
            }

        }
        // End addons list fetch

        $company->plan_staus = $status;
        $company->plan_expiry = $plan_expiry;
        $company->no_of_employee = $quantity;
        $company->plan_id = $plan_id;
        if ( $company->closure_plan == 1 ) {
            $company->payment_status = 'mark_uncollectible';
        } else {
            $company->payment_status = $payment_status;
        }
        if (!empty($plan_price_id)) {
            $company->price_id = $plan_price_id;
        }

        $company->save();

        // addons_history table expiry date update
        DB::table('addons_histories')->where('status',1)->update([
            'expiry_date' => $plan_expiry,
            'company_id'=>$company->id
        ]);

        $system_features = ModuleFeatureList::getFeaturesList($module_features_list)->getType(2)->get();

        if ($status == 'past_due') {
            // (new \App\Services\GracePeriod)->blockUsers($company);
        }
        try {
            Log::debug("TRIAL_ENDED_AND_PLAN_ACTIVE_STARTED_SUBSCRIPTION: " .json_encode($subscription));
            Log::debug("TRIAL_ENDED_AND_PLAN_ACTIVE_STARTED_SUBSCRIPTION_STATUS: " .$status);
            //trigger email if trial days equals to zero
            if (isset($subscription['previous_attributes']) && isset($subscription['previous_attributes']['status']) && $subscription['previous_attributes']['status'] == 'trialing' && $status == 'active') {


                if (empty($user)) {
                    $user = UserTableMaster::where(['id' => $company->super_admin_id])->first();
                }
                //fetch email body based on these conditions from database
                $emailConditions = ['service' => 'trial', 'status' => 1, 'trial' => 'complete_plan_active', 'days' => 0];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                if (is_null($email)) {
                    Log::debug("TRIAL_ENDED_AND_PLAN_ACTIVE_STARTED_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());
                }
                //Log::debug("TRIAL_ENDED_AND_PLAN_ACTIVE_STARTED_QUERY_RESULT: " . ($email != null ? json_encode($email->toArray()) : 'null'));

                $plan_detail = Products::where('stripe_id', $company->plan_id)->whereJsonContains('product->metadata->type', 'plans')->first();
                $productData = $plan_detail->product;

                $planTitle = $productData['metadata']['name'];
                $name = $user->first_name . ' ' . $user->last_name;
                /**
                 * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                 * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                 */
                $search = ['[x_name]', '[x_plan_name]'];
                $replace = [$name, $planTitle];
                (new \App\Services\StripeService())->sendMail($search, $replace, $user->id, $user->email, $email);

            }
        } catch (\Exception $e) {
            Log::debug("TRIAL_ENDED_AND_PLAN_ACTIVE_STARTED_ERROR: " . $e->getMessage());
            Log::debug("TRIAL_ENDED_AND_PLAN_ACTIVE_STARTED_ERROR_LINE: " . $e->getLine());
        }

        // Let's update Child Database
        try {
            if (DB::connection()->getDatabaseName() == getenv('DB_DATABASE')) {
                Masterdb::connect_company_db($company->company_initial);
            }

            $comp = [
                'plan_staus' => $status,
                'plan_expiry' => $plan_expiry,
                'plan_id' => $plan_id,
                'price_id' => $plan_price_id,
                'no_of_employee' => $quantity,
                'grace_period' => $company->grace_period,
            ];
            // DB::table("companies")->where('subscription_id', $subscription["id"])->update($comp);
            DB::table("companies")->where('id', $company->id)->update($comp);
            // addons_history table expiry date update
            DB::table('addons_histories')->where('status',1)->update([
                'expiry_date' => $plan_expiry
            ]);

            // Disable all the modules that are not in updated subscription list
            DB::table("modules")->whereNotIn('id', $module_list)->update(['status' => 0]);
            if (!empty($module_list)) {
                // Enable all the modules that are not in updated subscription list
                $all_subscription_module_parent_module_ids = DB::table('modules')
                ->whereIn('id', $module_list)
                ->pluck('parent_module_id')
                ->filter() // Removes null values to avoid unnecessary IDs
                ->toArray();

                $all_active_modules_ids = array_unique(array_merge($module_list, $all_subscription_module_parent_module_ids));

                DB::table('modules')
                    ->whereIn('id', $all_active_modules_ids)
                    ->update(['status' => 1]);
            }

            // Disable all the features that are not in updated subscription list
            if (!$company->closure_plan) {
                DB::table("system_features")->whereNotIn('feature_key', $module_features_list)->update(['status' => 2]);

                // Enable all the features that are in the updated subscription list
                if (!empty($system_features)) {
                    foreach ($system_features as $key => $system_feature) {
                        DB::table("system_features")
                            ->where('feature_key', $system_feature['feature_key'])
                            ->where('parent_module_id', $system_feature['parent_module_id'])
                            ->where('sub_module_id', $system_feature['sub_module_id'])
                            ->update([
                                    'status' => 1,
                                    'package_id' => $plan_id,
                                    'feature_value' => $system_feature['feature_value']
                                ]
                            );
                    }
                }
            }
            if ($status == 'unpaid') {
                // (new \App\Services\GracePeriod)->switchToClosurePlan($company);
            }
        } catch (\Exception $exception) {
            Log::channel('stripe_webhook')->error("File: " . $exception->getFile() . " Line:" . $exception->getLine());
            Log::channel('stripe_webhook')->error($exception->getMessage());
        }


        return true;
    }

    public function invoice($event)
    {

        Log::channel('stripe_webhook')->info('Inside StripWebhookService.php => invoice() :: event = ' . json_encode($event));
        if ($event["type"] == "invoice.upcoming") {
            return true;
        }
        if (!isset($event["data"]["object"]["id"])) {
            Log::channel('stripe_webhook')->error('Invoice_id not found in the data.');
            return false;
        }
        Masterdb::connect_master_db();
        $invoice_id = $event["data"]["object"]["id"];
        $stripe_customer_id = $event["data"]["object"]["customer"];
        $subscription_id = $event["data"]["object"]["subscription"];
        $status = $event["data"]["object"]["status"];
        $company = $this->getCompany($subscription_id);

        if ( empty($company) ) {
            Log::channel('stripe_webhook')->info('StripeWebHookService->invoice() Company Not Found with SubscriptionId=' . $subscription_id);
            return false;
        }

        if ( !empty($company) && $company->payment_status == "mark_uncollectible" ) {
            Log::channel('stripe_webhook')->info('Subscrioption status "mark_uncollectible" =' . $subscription_id);
            return false;
        }

        $invoice = Invoices::where('invoice_id', $invoice_id)->first();
        if (empty($invoice)) {
            $invoice = new Invoices();
        }
        $invoice->invoice_id = $invoice_id;
        $invoice->stripe_customer_id = $stripe_customer_id;
        $invoice->subscription_id = $subscription_id;
        $invoice->status = $status;
        $invoice->invoice = $event["data"]["object"];
        $invoice->save();

        if ($status == 'paid') {
            // Reset Subscription Table
            $subscription = Subscriptions::where('subscription_id', $subscription_id)->first();
            if (!empty($subscription)) {
                (new \App\Services\GracePeriod)->unBlockUsers($company, $subscription);
                $company = Company::where('id', $company->id)->first();
                $subscription->delete();

                $company->closure_plan = 0;
            }
            if (!$company->closure_plan) {
                (new \App\Services\GracePeriod)->unBlockUsers($company);
            }
            $company->grace_period = null;
            $company->grace_period_start = null;
            $company->grace_period_start_date = null;
            $company->grace_period_name = null;

            $company->save();

            // let's update child database
            try {
                Masterdb::connect_company_db($company->company_initial);
                $comp = [
                    'grace_period' => null,
                    'grace_period_start' => null,
                    'grace_period_name' => null,
                ];
                // DB::table("companies")->where('subscription_id', $subscription_id)->update($comp);
                DB::table("companies")->where('id', $company->id)->update($comp);
            } catch (\Exception $exception) {
                Log::channel('stripe_webhook')->error("File: " . $exception->getFile() . " Line:" . $exception->getLine());
                Log::channel('stripe_webhook')->error($exception->getMessage());
            }
            return $invoice;
        }
        // if($status == 'open' && empty($company->grace_period_start)) {
        //     $company->grace_period_start = date('Y-m-d H:i:s');
        // }

        $settingController = new SettingsAdminController();
        $setting = $settingController->index();
        /**
         *      Pseudo code as disscussed with @Farzand ali and @Taseer Haider at 24-July-2024
         *      plan_status = active and ivoice = [draft]             =====> Strat grace period
         *      plan_status = past_due and invoice = [open/draft]     =====> pre grace period
         *      plan_status = unpaid grace period                     =====> post grace period
         */

        if ($company->plan_staus == 'trialing' && in_array($status, ['open'])) {

            $company->grace_period = (int)$setting->trail_grace_period;
            \Log::info("StripeWebhookService -> invoice() plan_status === trialing");
        }
        if ($company->plan_staus == 'past_due' && in_array($status, ['open'])) {
            $company->grace_period = (int)$setting->grace_period->value;
            if ($company->grace_period_name != "grace_period") {
                $company->grace_period_name = 'grace_period';
                $company->grace_period_start = date('Y-m-d H:i:s');
                $company->grace_period_start_date = date('Y-m-d H:i:s');

                (new \App\Services\GracePeriod)->grace_period_start($company);
            }
            \Log::info("StripeWebhookService -> invoice() plan_status === past_due");
        }

        if ($company->plan_staus == 'unpaid') {
            $company->grace_period = (int)$setting->post_grace_period->value;
            if ($company->grace_period_name != "post_grace_period") {
                $company->grace_period_name = 'post_grace_period';
                $company->grace_period_start = date('Y-m-d H:i:s');
                (new \App\Services\GracePeriod)->post_grace_period($company);
                (new \App\Services\GracePeriod)->blockUsers($company);
            }
            \Log::info("StripeWebhookService -> invoice() plan_status === Unpaid");
        }
        $company->save();

        // Let's update child database
        try {
            Masterdb::connect_company_db($company->company_initial);
            $comp = [
                'grace_period' => $company->grace_period,
                'grace_period_name' => $company->grace_period_name,
                'grace_period_start' => $company->grace_period_start,
            ];

            // DB::table("companies")->where('subscription_id', $subscription_id)->update($comp);
            DB::table("companies")->where('id', $company->id)->update($comp);
        } catch (\Exception $exception) {
            Log::channel('stripe_webhook')->error("File: " . $exception->getFile() . " Line:" . $exception->getLine());
            Log::channel('stripe_webhook')->error($exception->getMessage());
        }
        return $invoice;
    }

    private function getCompany($subscription_id)
    {
        $company = Company::where('subscription_id', $subscription_id)->first();
        if (empty($company)) {
            $subscription = Subscriptions::where('subscription_id', $subscription_id)->first();
            if (!empty($subscription)) {
                $company = Company::where('id', $subscription->company_id)->first();
            }
        }
        return $company;
    }

}
