<?php

namespace App\Services;

use App\Libraries\Generic;
use App\Models\Company;
use App\Models\Settings\Email;
use App\Models\UserCompanies;
use Illuminate\Support\Facades\DB;
use App\Libraries\Masterdb;
use App\Models\UserTableMaster;
use App\Models\Subscriptions;
use App\Http\Controllers\SettingsAdminController;
use Illuminate\Support\Facades\Log;

class GracePeriod
{
    private $blockUsersIds = [];

    public function index(): void
    {
        \Log::info('GracePeriod service scheduler executed at ' . date('Y-m-d h:i:s'));

        $this->grace_period_start();
        $this->post_grace_period();
        $this->post_grace_period_end();

    }

    public function grace_period_start($comp = null)
    {


        Masterdb::connect_master_db();
        // Active Plan Grace Period
        // DB::enableQueryLog();
        try {
            if(is_null($comp)){
                $companies = Company::where('grace_period', '!=', null)
                ->where('plan_staus', '=', 'past_due')
                ->whereRaw("CURDATE() <= DATE(grace_period_start) + INTERVAL grace_period DAY")
                ->get();
            }
            else{
                $companies = collect([$comp]);
            }

            Log::debug("GRACE_PERIOD_START_COMPANIES: " .  json_encode($companies->toArray()) );
            Log::debug("GRACE_PERIOD_START_RESULT_CHECK: " .  !$companies->isEmpty() );

            // $query = DB::getQueryLog();
            if (!$companies->isEmpty()) {
                foreach ($companies as $key => $company) {
                    if(!is_null($comp)){
                        $company = (object)$company;
                    }

                    if (!empty($company->grace_period_start) && $company->grace_period_name == "grace_period" ) {

                        // Given start date (time component is ignored)
                        $startDate = new \DateTime(trim($company->grace_period_start_date));
                        // Current date (time component is ignored)
                        $today = new \DateTime('today');
                        // Calculate the difference
                        $interval = $today->diff($startDate);
                        // Get the number of days difference, with sign
                        $daysDifference = $interval->days;
                        Log::debug("GRACE_PERIOD_START_DIFFERENCE: Company=".$company->company_initial.",".  $daysDifference);

                        if ($daysDifference <= $company->grace_period) {
                            //fetch email body based on these conditions from database
                            $emailConditions = ['service' => 'trial', 'status' => 1, 'trial' => 'payment_declined', 'days' => $daysDifference];
                            $email = Email::where($emailConditions)->whereNull('deleted_at')->first();

                            Log::debug("GRACE_PERIOD_START_RAW_QUERY: Company=".$company->company_initial." : " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());

                            //Log::debug("GRACE_PERIOD_START_QUERY_RESULT: " . $email != null ? json_encode($email->toArray()) : null);
                            $admins = $company->company_admin_emails != null ? $company->company_admin_emails['ids'] : [];

                            if ($admins != null && count($admins) > 0) {

                                foreach ($admins as $user) {
                                    $uData = null;

                                    if (!empty($user)) {
                                        $uData = UserTableMaster::where(['id' => $user])->first();
                                    }

                                    $name = $uData->first_name . ' ' . $uData->last_name;
                                    /**
                                     * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                                     * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                                     */
                                    $search = ['[x_name]', '[x_days]'];
                                    $replace = [$name, ((int)$company->grace_period) - (int)$daysDifference];
                                    (new \App\Services\StripeService())->sendMail($search, $replace, $uData->id, $uData->email, $email);
                                }
                            }

                            // Notify Client Via Emails Faizan
                            // dispatch(new \App\Jobs\GracePeriodJob($company->toArray(), $admins->toArray()))->onQueue('emails');
                        }


                    }


                }
            }
        } catch (\Exception $e) {
            Log::debug("GRACE_PERIOD_START_ERROR: " . $e->getMessage());
            Log::debug("GRACE_PERIOD_START_ERROR_LINE: " . $e->getLine());
        }
    }

    public function post_grace_period($comp = null)
    {

        Masterdb::connect_master_db();

        if(is_null($comp)){
            // Pre Grace Period
            $companies = Company::where('grace_period', '!=', null)
            ->join('users', 'users.id', '=', 'companies.super_admin_id')
            ->where('plan_staus', '=', 'unpaid')
            ->whereRaw("CURDATE() <= DATE(grace_period_start) + INTERVAL grace_period DAY")
            ->get();
        } else {
            $companies = collect([$comp]);
        }

        try {

            Log::debug("GRACE_PERIOD_START_COMPANIES: " . $companies != null ? json_encode($companies->toArray()) : null);

            // $query = DB::getQueryLog();
            if (!$companies->isEmpty()) {
                foreach ($companies as $key => $company) {
                    if(!is_null($comp)){
                        $company = (object)$company;
                    }
                    if (!empty($company->grace_period_start) && $company->grace_period_name == "post_grace_period" ) {

                        // Given start date (time component is ignored)
                        $startDate = new \DateTime(trim($company->grace_period_start_date));
                        // Current date (time component is ignored)
                        $today = new \DateTime('today');
                        // Calculate the difference
                        $interval = $today->diff($startDate);
                        // Get the number of days difference, with sign
                        $daysDifference = $interval->days;
                        Log::debug("POST_GRACE_PERIOD_START_DIFFERENCE: Company=".$company->company_initial." : " .  $daysDifference);
                        if ($daysDifference <= $company->grace_period) {
                            //fetch email body based on these conditions from database
                            $emailConditions = ['service' => 'trial', 'status' => 1, 'trial' => 'payment_declined', 'days' => $daysDifference];
                            $email = Email::where($emailConditions)->whereNull('deleted_at')->first();

                            Log::debug("POST_GRACE_PERIOD_START_RAW_QUERY: Company=".$company->company_initial." : " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());

                            //Log::debug("GRACE_PERIOD_START_QUERY_RESULT: " . $email != null ? json_encode($email->toArray()) : null);
                            $admins = $company->company_admin_emails != null ? $company->company_admin_emails['ids'] : [];
//                      $admins = Company::CompanyWithUsers($company->id)->whereIn('profile_type', ["Owner", "Admin"])->where('companies_users.status', 'active')->get();
//                      $blockUsers = Company::CompanyWithUsers($company->id)->whereIn('profile_type', ["User", "Manager"])->where('companies_users.status', 'active')->get();

                            if ($admins != null && count($admins) > 0) {

                                foreach ($admins as $user) {
                                    $uData = null;

                                    if (!empty($user)) {
                                        $uData = UserTableMaster::where(['id' => $user])->first();
                                    }
                                    $name = $uData->first_name . ' ' . $uData->last_name;
                                    /**
                                     * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                                     * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                                     */
                                    $search = ['[x_name]', '[x_days]'];
                                    $replace = [$name, ((int)$company->grace_period) - (int)$daysDifference];
                                    (new \App\Services\StripeService())->sendMail($search, $replace, $uData->id, $uData->email, $email);
                                }
                            }

                            // Notify Client Via Emails Faizan
                            // dispatch(new \App\Jobs\GracePeriodJob($company->toArray(), $admins->toArray()))->onQueue('emails');
                        }


                    }


                }
            }
        } catch (\Exception $e) {
            Log::debug("POST_GRACE_PERIOD_START_ERROR: " . $e->getMessage());
            Log::debug("POST_GRACE_PERIOD_START_ERROR_LINE: " . $e->getLine());
        }


        $post_grace_period_companies = Company::where('grace_period', '!=', null)
        ->where('plan_staus', '=', 'unpaid')
        ->where('grace_period_name', '=', 'post_grace_period')
        ->whereRaw("CURDATE() > DATE(grace_period_start) + INTERVAL grace_period DAY")
        ->get();
        if (!$post_grace_period_companies->isEmpty()) {
            $settingController = new SettingsAdminController();
            $setting = $settingController->index();
            foreach ($post_grace_period_companies as $key => $company) {
                $company->grace_period = (int)$setting->post_grace_period_end;
                $company->grace_period_name = 'post_grace_period_end';
                $company->grace_period_start = date('Y-m-d H:i:s');
                $company->save();
            }
        }
    }

    private function post_grace_period_end()
    {
        Masterdb::connect_master_db();
        $companies = Company::where('grace_period', '!=', null)
        ->where('plan_staus', '=', 'unpaid')
        ->where('grace_period_name', '=', 'post_grace_period_end')
        ->whereRaw("CURDATE() <= DATE(grace_period_start) + INTERVAL grace_period DAY")
        ->get();

        try {

            Log::debug("GRACE_PERIOD_END_COMPANIES: " . $companies != null ? json_encode($companies->toArray()) : null);

            // $query = DB::getQueryLog();
            if (!$companies->isEmpty()) {
                foreach ($companies as $key => $company) {

                    if (!empty($company->grace_period_start) && $company->grace_period_name == "post_grace_period_end" ) {

                        // Given start date (time component is ignored)
                        $startDate = new \DateTime(trim($company->grace_period_start_date));
                        // Current date (time component is ignored)
                        $today = new \DateTime('today');
                        // Calculate the difference
                        $interval = $today->diff($startDate);
                        // Get the number of days difference, with sign
                        $daysDifference = $interval->days;

                        if ($daysDifference <= $company->grace_period) {
                            //fetch email body based on these conditions from database
                            $emailConditions = ['service' => 'trial', 'trial' => 'payment_declined', 'status' => 1, 'days' => $daysDifference];
                            $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                            if (is_null($email)) {
                                Log::debug("POST_GRACE_PERIOD_END_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());
                            }
                            Log::debug("GRACE_PERIOD_END_QUERY_RESULT: " . $email != null ? $email->id : null);
                            $admins = $company->company_admin_emails != null ? $company->company_admin_emails['ids'] : [];
//                      $admins = Company::CompanyWithUsers($company->id)->whereIn('profile_type', ["Owner", "Admin"])->where('companies_users.status', 'active')->get();
//                      $blockUsers = Company::CompanyWithUsers($company->id)->whereIn('profile_type', ["User", "Manager"])->where('companies_users.status', 'active')->get();

                            if ($admins != null && count($admins) > 0) {

                                foreach ($admins as $user) {
                                    $uData = null;

                                    if (!empty($user)) {
                                        $uData = UserTableMaster::where(['id' => $user])->first();
                                    }
                                    $name = $uData->first_name . ' ' . $uData->last_name;
                                    /**
                                     * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                                     * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                                     */
                                    $search = ['[x_name]', '[x_days]'];
                                    $replace = [$name, ((int)$company->grace_period) - (int)$daysDifference];
                                    (new \App\Services\StripeService())->sendMail($search, $replace, $uData->id, $uData->email, $email);
                                }
                            }

                            // Notify Client Via Emails Faizan
                            // dispatch(new \App\Jobs\GracePeriodJob($company->toArray(), $admins->toArray()))->onQueue('emails');
                        }


                    }


                }
            }
        } catch (\Exception $e) {
            Log::debug("POST_GRACE_PERIOD_END_ERROR: " . $e->getMessage());
            Log::debug("POST_GRACE_PERIOD_END_ERROR_LINE: " . $e->getLine());
        }

        $companies_need_to_switch = Company::where('grace_period', '!=', null)
        ->where('plan_staus', '=', 'unpaid')
        ->where('grace_period_name', '=', 'post_grace_period_end')
        ->whereRaw("CURDATE() > DATE(grace_period_start) + INTERVAL grace_period DAY")
        ->get();
        // dd('Post Grace Period End',$companies_need_to_switch);
        if (!$companies_need_to_switch->isEmpty()) {
            foreach ($companies_need_to_switch as $key => $company) {
                // $this->blockUsers($company);
                $this->switchToClosurePlan($company);
            }
        }
    }


    public function blockUsers($company, $blockAdmins = false)
    {
        \Log::info('I am in inside Block Users....');
        if (DB::connection()->getDatabaseName() != getenv('DB_DATABASE')) {
            Masterdb::connect_master_db();
        }
        $adminIds = $company->company_admin_emails;

        if (!empty($adminIds)) {
            $adminIds = $adminIds['ids'];
        }
        // \Log::info('adminIds => blockUsers() company_id: ' . $company->id . ' BlockUsers: ' . implode(',', $adminIds));
        if($blockAdmins) {
            $adminIds = [];
        } 
        $blockUsers = UserCompanies::where(['company_id' => $company->id, 'companies_users.status' => 'active']);
        $blockUsersAdmin = clone $blockUsers;
        if(!empty($adminIds)) {
            $blockUsers = $blockUsers->whereNotIn('user_id', $adminIds);
        }
        // \Log::info(vsprintf(str_replace(['?'], ['\'%s\''], $blockUsers->toSql()), $blockUsers->getBindings()));
        $blockUsers = $blockUsers->get();
        $blockAdminUsers = $blockUsersAdmin->get();
        $blockUsersIds = (!$blockUsers->isEmpty()) ? $blockUsers->pluck('user_id')->toArray() : [];
        $blockAdminUsersIds = (!$blockAdminUsers->isEmpty()) ? $blockAdminUsers->pluck('user_id')->toArray() : [];
        // \Log::info('blockUsers => blockUsers() company_id: ' . $company->id . ' BlockUsers: ' . implode(',', $blockUsers));
        if ( !empty($blockUsersIds) ) {
            UserCompanies::where('company_id', $company->id)->whereIn('user_id', $blockUsersIds)->update([
                'status' => 'deactive',
                'blocked_by_super_admin' => 1
            ]);
        }
        
        if ( !empty($blockAdminUsersIds) ) {
            UserCompanies::where('company_id', $company->id)->whereIn('user_id', $blockAdminUsersIds)->update([
                'blocked_by_super_admin' => 1
            ]);
        }

        \Log::info('Inside GracePeriod.php => blockUsers() company_id: ' . $company->id . ' BlockUsers: ' . implode(',', $blockUsersIds));
        Masterdb::connect_company_db($company->company_initial);
        if (!empty($blockAdminUsersIds)) {
            // Send Block users Notifications.
            $gen = new Generic();
            try {
                $gen->user_blocked_notification($company->id, $blockAdminUsersIds);
            } catch (\Exception $exception) {
                \Log::error('Inside GracePeriod.php => blockUsers() During Block user notifications Error Message: ' . $exception->getMessage());
            }
        }
        if (!empty($blockUsersIds)) {
            // Block users from the company Database.
            DB::table('companies_users')->wherein('user_id', $blockUsersIds)->update([
                'status' => 'deactive',
                'blocked_by_super_admin' => 1
            ]);
        }
        // Deactivate Current Active Modules.
        $modules = DB::table('modules')->get();
        foreach ($modules as $key => $module) {
            if ($module->status == 1 && $module->id != 81) {
                // module id = 81 is for billing module. It will always Active
                $mod_ids[] = $module->id;
            }
        }
        DB::table('modules')->whereIn('id', $mod_ids)->update(['status' => 0, 'deactive_by_super_admin' => 1]);
        DB::table('modules')->where(['id' => 81])->update(['status' => 1, 'deactive_by_super_admin' => 0]);
    }

    public function unBlockUsers($company, $subscription = null)
    {
        $closurePlanSubscriptionId = null;
        if (DB::connection()->getDatabaseName() != getenv('DB_DATABASE')) {
            Masterdb::connect_master_db();
        }

        UserCompanies::where(['company_id' => $company->id, 'blocked_by_super_admin' => 1])->update([
            'status' => 'active',
            'blocked_by_super_admin' => 0
        ]);
        if (!empty($subscription)) {
            $stripe = new StripeService();
            $stripeSubscriptionData = $stripe->getSubscription($subscription->subscription_id);
            $closurePlanSubscriptionId = $company->subscription_id;
            $company->plan_staus = $stripeSubscriptionData->status;
            $company->plan_id = $subscription->plan_id;
            $company->price_id = $subscription->price_id;
            $company->subscription_id = $subscription->subscription_id;
            $company->plan_expiry = date('Y-m-d H:i:s', $subscription->current_period_end);
            $company->closure_plan = 0;
            $company->save();
        }

        Masterdb::connect_company_db($company->company_initial);

        // Restore Company table subscription
        if (!empty($subscription)) {
            DB::table('companies')->where(['id' => $company->id])->update(
                [
                    'plan_staus' => $company->plan_staus,
                    'plan_id' => $company->plan_id,
                    'price_id' => $company->price_id,
                    'plan_expiry' => $company->plan_expiry,
                    'subscription_id' => $company->subscription_id,
                    'closure_plan' => 0,
                ]
            );
        }

        // Activate users from Company DB
        DB::table('companies_users')->where(['blocked_by_super_admin' => 1])->update(['status' => 'active', 'blocked_by_super_admin' => 0]);
        // Activate Current Active Modules.
        DB::table('modules')->where(['deactive_by_super_admin' => 1])->update(['status' => 1, 'deactive_by_super_admin' => 0]);
        DB::table('modules')->where(['id' => 81])->update(['status' => 1, 'deactive_by_super_admin' => 0]);// Billing module will always enable.

        if (!empty($closurePlanSubscriptionId)) {
            $stripe->cancelSubscription($closurePlanSubscriptionId); // Let's cancel the closure plan subscription
        }

        Masterdb::connect_master_db();


    }

    public function switchToClosurePlan($company)
    {
        Masterdb::connect_master_db();
        $user = UserTableMaster::find($company->super_admin_id);

        $stripe = new StripeService();
        $products = $stripe->searchProducts([
            'query' => 'active:\'true\' AND name:\'Closure Plan\'',
        ]);
        $price = '';
        if (!empty($products)) {
            foreach ($products as $key => $product) {
                if (strtolower($product['name']) == 'closure plan') {
                    $productDetail = $stripe->product($product['id'], true);
                    if (!empty($productDetail['prices'])) {
                        foreach ($productDetail['prices'] as $key => $price) {
                            if ($price->active) {
                                $price = $price->id;
                                break;
                            }
                        }
                    }
                    break;
                }
            }
        }

        if (!empty($company->subscription_id)) {
            Subscriptions::updateOrCreate(
            // Find
                [
                    'subscription_id' => $company->subscription_id,
                    'company_id' => $company->id,
                ],
                // Add or Update
                [
                    'company_id' => $company->id,
                    'super_admin_id' => $company->super_admin_id,
                    'subscription_id' => $company->subscription_id,
                    'plan_id' => $company->plan_id,
                    'price_id' => $company->price_id,
                    'plan_staus' => $company->plan_staus,
                    'plan_expiry' => $company->plan_expiry,
                    'grace_period_start' => $company->grace_period_start,
                ]
            );
            // $customer = $stripe->retrieveCustomer($user->stripe_customer_id, ['expand' => ['subscriptions']])->toArray();
            // dd($customer);
            \Log::error('Closure Plan Data price:' . json_encode($price));
            if (!empty($price)) {
                $post = [
                    "stripe_customer_id" => $user->stripe_customer_id,
                    "company_id" => $company->id,
                    "user_id" => $company->super_admin_id,
                    "price_ids" => [$price],
                    "payment_status" => 'mark_uncollectible',
                    "closure_plan" => 1,
                ];
                \Log::error('Closure Plan Data:' . json_encode($post));
                $this->block_users_close_plan($company);
                return $stripe->createSubscription($post);
            }
        }


    }

    private function block_users_close_plan($company, $blockAdmins = false)
    {
        $adminIds = $company->company_admin_emails;
        if (!empty($adminIds)) {
            $adminIds = $adminIds['ids'];
        }
        $blockUsers = UserCompanies::where(['company_id' => $company->id, 'companies_users.status' => 'active']);
        $blockUsersAdmin = clone $blockUsers;
        if($blockAdmins) {
            $adminIds = [];
        } 
        if(!empty($adminIds)) {
            $blockUsers = $blockUsers->whereNotIn('user_id', $adminIds);
        }
        \Log::info(vsprintf(str_replace(['?'], ['\'%s\''], $blockUsers->toSql()), $blockUsers->getBindings()));
        $blockUsers = $blockUsers->get();
        $blockAdminUsers = $blockUsersAdmin->get();
        $blockUsersIds = (!$blockUsers->isEmpty()) ? $blockUsers->pluck('user_id')->toArray() : [];
        $blockAdminUsersIds = (!$blockAdminUsers->isEmpty()) ? $blockAdminUsers->pluck('user_id')->toArray() : [];
        if ( !empty($blockUsersIds) ) {
            UserCompanies::where('company_id', $company->id)->whereIn('user_id', $blockUsersIds)->update([
                'status' => 'deactive',
                'blocked_by_super_admin' => 1
            ]);
        }
        
        if ( !empty($blockAdminUsersIds) ) {
            UserCompanies::where('company_id', $company->id)->whereIn('user_id', $blockAdminUsersIds)->update([
                'blocked_by_super_admin' => 1
            ]);
        }

        Masterdb::connect_company_db($company->company_initial);
        if (!empty($blockAdminUsersIds)) {
            $gen = new Generic();
            try {
                $gen->user_blocked_notification($company->id, $blockAdminUsersIds);
            } catch (\Exception $exception) {
                \Log::error('Inside GracePeriod.php => blockUsers() During Block user notifications Error Message: ' . $exception->getMessage());
            }
        }
        if (!empty($blockUsersIds)) {
            // Send Block users Notifications.
            // Block users from the company Database.
            DB::table('companies_users')->wherein('user_id', $blockUsersIds)->update([
                'status' => 'deactive',
                'blocked_by_super_admin' => 1
            ]);
        }
        Masterdb::connect_master_db();
    }

    private function post_grace_periodOLD()
    {
        Masterdb::connect_master_db();
        // Pre Grace Period
        $companies = Company::where('grace_period', '!=', null)
        ->join('users', 'users.id', '=', 'companies.super_admin_id')
        ->where('plan_staus', '=', 'past_due')
        ->whereRaw("CURDATE() <= DATE(grace_period_start) + INTERVAL grace_period DAY")
        ->get();
        // $query = DB::getQueryLog();
        if (!$companies->isEmpty()) {
            // dd($companies->toArray());
            $notifications = [];
            foreach ($companies as $key => $company) {
                $admins = Company::CompanyWithUsers($company->id)->whereIn('profile_type', ["Owner", "Admin"])->where('companies_users.status', 'active')->get();
                $blockUsers = Company::CompanyWithUsers($company->id)->whereIn('profile_type', ["User", "Manager"])->where('companies_users.status', 'active')->get();

                $blockUsersIds = (!$blockUsers->isEmpty()) ? $blockUsers->pluck('user_id')->toArray() : [];
                $adminIds = (!$admins->isEmpty()) ? $admins->pluck('user_id')->toArray() : [];

                $notifications[$key]['company'] = $company->toArray();
                $notifications[$key]['admins'] = $adminIds;
                $notifications[$key]['blockUsers'] = $blockUsersIds;

                // Notify Client Via Emails Faizan
                // dispatch(new \App\Jobs\GracePeriodJob($company->toArray(), $admins->toArray()))->onQueue('emails');

                // Block users from master database.
                if (!$blockUsers->isEmpty()) {
                    UserCompanies::where('company_id', $company->id)->whereIn('user_id', $blockUsers->toArray())->update([
                        'status' => 'deactive',
                        'blocked_by_super_admin' => 1
                    ]);
                }
            }

            // line for testing purpose
            $notifications[++$key]['company'] = Company::where('id', 40)->first()->toArray();
            $notifications[$key]['blockUsers'] = Company::CompanyWithUsers(40)
            ->whereIn('profile_type', ["User", "Manager"])
            ->where("companies_users.status", "active")
            ->whereIn('users.id', [201, 57])
            ->pluck('user_id')
            ->toArray();
            // End line for testing purpose

            if (!empty($notifications)) {
                $gen = new Generic();
                foreach ($notifications as $key => $notification) {
                    $company_initial = $notification['company']['company_initial'];
                    Masterdb::connect_company_db($company_initial);

                    // Block All the users.
                    if (!empty($notification['blockUsers'])) {
                        $gen->user_blocked_notification($notification['company']['id'], $notification['blockUsers']);
                    }

                    // Deactivate Current Active Modules.
                    $modules = DB::table('modules')->get();
                    foreach ($modules as $key => $module) {
                        if ($module->status == 1 && $module->id != 81) {
                            // module id = 81 is for billing module. It will always Active
                            DB::table('modules')->where('id', $module->id)->update(['status' => 0, 'deactive_by_super_admin' => 1]);
                        }
                        // testing
                        // if($module->status == 0 && $module->deactive_by_super_admin == 1) {
                        //     DB::table('modules')->where('id', $module->id)->update(['status' => 1 , 'deactive_by_super_admin' => 0 ]);
                        // }
                        // End testing.
                    }
                }
            }
        }
    }
}
