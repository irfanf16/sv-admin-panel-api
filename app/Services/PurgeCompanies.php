<?php

namespace App\Services;

use App\Models\Company;
use App\Services\StripeService;
use App\Models\Subscriptions;
use Illuminate\Support\Facades\DB;
class PurgeCompanies
{
    public function purge(): array
    {
        return [];
        \Log::info('PurgeCompanies service scheduler executed at '. date('Y-m-d h:i:s'));
        $subscriptions = Subscriptions::where('grace_period','!=', null)
            ->whereRaw("CURDATE() > DATE(grace_period_start) + INTERVAL grace_period+3 DAY")
            ->get();
        $deleteSubscriptionsIds = [];
        $companies = [];
        if(!$subscriptions->isEmpty()) {
            $stripe = new StripeService();
            foreach ($subscriptions as $key => $subscription) {
                // $purge_date = date('Y-m-d', strtotime($company->grace_period_start. ' + '. (int) $company->grace_period.' days'));
                // $todays_date = date('Y-m-d');
                // if($todays_date >= $purge_date) {
                    
                        $company = Company::where('id', $subscription->company_id)->first();
                        if(!empty($company)) {
                            try {
                                $stripe->cancelSubscription($subscription->subscription_id);
                                $stripe->cancelSubscription($company->subscription_id);
                                DB::statement("DROP DATABASE IF EXISTS `{$company->company_initial}`");
                                $company->status = 2;
                                $company->save();
                                $companies[] = $company;
                                \Log::info("Database: `{$company->company_initial}` has deleted.");
                                $deleteSubscriptionsIds[] = $subscription->id;
                            } catch (\Exception $ex) {
                                \Log::error("Unable to Delete Database: `{$company->company_initial}` due to `{$ex->getMessage()}`");
                            }
                        }
                        
                    
                // }
                
            }
            if(!empty($deleteSubscriptionsIds)) {
                Subscriptions::whereIn('id', $deleteSubscriptionsIds)->delete();
            }
            return [
                'companies' => $companies,
            ];
        }
        return ['companies'=>[]];
    }
    public function purgeOLD(): array
    {
        \Log::info('PurgeCompanies service scheduler executed at '. date('Y-m-d h:i:s'));
        $companies = Company::where('grace_period','!=', null)
            ->where('status','!=',2 )
            ->where('plan_staus','=','canceled')
            ->get();

        if(!$companies->isEmpty()) {
            foreach ($companies as $key => $company) {
                // $purge_date = date('Y-m-d', strtotime($company->grace_period_start. ' + '. (int) $company->grace_period.' days'));
                // $todays_date = date('Y-m-d');
                // if($todays_date >= $purge_date) {
                    try {
                        DB::statement("DROP DATABASE IF EXISTS `{$company->company_initial}`");
                        $company->status = 2;
                        $company->save();
                        \Log::info("Database: `{$company->company_initial}` has deleted.");
                    } catch (\Exception $ex) {
                        \Log::error("Unable to Delete Database: `{$company->company_initial}` due to `{$ex->getMessage()}`");
                    }
                // }
                
            }
            return [
                'companies' => $companies->toArray(),
            ];
        }
        return ['companies'=>[]];
    }
}
