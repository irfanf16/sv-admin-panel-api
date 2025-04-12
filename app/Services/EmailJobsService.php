<?php

namespace App\Services;

use App\Models\Company;
use App\Models\Products;
use App\Mail\XDaysLeftEmail;
use App\Services\StripeService;

class EmailJobsService
{
    public function midTrialEmail(): array
    {
        $companies = Company::companyWithActiveTrial()->get();
        if(!$companies->isEmpty()) {
            $stripe = new StripeService();
            foreach ($companies as $key => $company) {
                $price_id = $company->price_id;
                $date = new \Carbon\Carbon($company->plan_expiry);  
                $today_diff_in_days = $date->diffInDays();

                $subscription = $stripe->getSubscription($company->subscription_id);
                $subscription_start_date = date('Y-m-d H:i:s',$subscription->trial_start);
                $subscription_end_date = date('Y-m-d H:i:s',$subscription->trial_end);

                $date = new \Carbon\Carbon($subscription_end_date);  
                $trial_total_days = $date->diffInDays($subscription_start_date);
                $mid = number_format( $trial_total_days / 2) ;
            
                $productInstance = Products::getProducts([
                    'prices' => [
                        'id' => $price_id,
                    ]
                ])->first();
        
                if(!empty($productInstance)) {
                    $product = $productInstance->product;
                    foreach ($product['prices'] as $key => $price) {
                        if($price['id'] == $price_id) {
                            break;
                        }
                    }
                } else {
                    // in case someone delete the price accedently from db, we'll search the price on stripe
                    $price = $stripe->getPriceByid($price_id)->toArray();
                    $product = $stripe->product($price['product'], false);
                    if(!empty($product)) {
                        $product = $product['product'];
                        $product['price'] = $price;
                    }
                }
                $company->plan_name = $product['name'];
                $company->interval = $price['recurring']['interval'];

                $company->midDays = $mid;
                $company->today_diff_in_days = $today_diff_in_days;
                if($mid == $today_diff_in_days) {
                    // $company->email = 'adnan.crecentech@gmail.com';
                    dispatch(new \App\Jobs\XDaysLeftJob($company->toArray()))->onQueue('emails');
                }  
                
            }
            return [
                'company' => $company->toArray(),
            ];
        }
        return [];
    }

    public function lastDayTrialEmail(): array
    {
        $companies_list = [];
        $companies = Company::companyWithActiveTrial()->get();
        if(!$companies->isEmpty()) {
            $stripe = new StripeService();
            foreach ($companies as $key => $company) {
                $price_id = $company->price_id;
                $date = new \Carbon\Carbon($company->plan_expiry);  
                $today_diff_in_days = $date->diffInDays();

                $subscription = $stripe->getSubscription($company->subscription_id);
                $productInstance = Products::getProducts([
                    'prices' => [
                        'id' => $price_id,
                    ]
                ])->first();
        
                if(!empty($productInstance)) {
                    $product = $productInstance->product;
                    foreach ($product['prices'] as $key => $price) {
                        if($price['id'] == $price_id) {
                            break;
                        }
                    }
                } else {
                    // in case someone delete the price accedently from db, we'll search the price on stripe
                    $price = $stripe->getPriceByid($price_id)->toArray();
                    $product = $stripe->product($price['product'], false);
                    if(!empty($product)) {
                        $product = $product['product'];
                        $product['price'] = $price;
                    }
                }
                $company->plan_name = $product['name'];
                $company->interval = $price['recurring']['interval'];
                $company->today_diff_in_days = $today_diff_in_days;

                $subscription_end_date = date('Y-m-d',$subscription->trial_end);
                if($subscription_end_date == date('Y-m-d')) {
                    dispatch(new \App\Jobs\LastDayOfTrialJob($company->toArray()))->onQueue('emails');
                }
            
                // $company->email = 'adnan.crecentech@gmail.com';
                // dispatch(new \App\Jobs\LastDayOfTrialJob($company->toArray()))->onQueue('emails');
                $companies_list[]  = $company->toArray();
            }
            return [
                'company' => $companies_list,
            ];
        }
        return [];
    }
}
