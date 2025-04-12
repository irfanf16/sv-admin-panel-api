<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Historable;
use Laracasts\Presenter\PresentableTrait;
use App\Presenters\AddonsHistoryPresenter;
use Illuminate\Support\Str;
use App\Libraries\Masterdb;
use App\Services\StripeService;
use Carbon\Carbon;
use Modules\Plans\Http\Controllers\SubscriptionController;

class AddonsHistory extends Model
{
    use HasFactory;
    use Historable;
    use PresentableTrait;
    protected $primaryKey = "id";
    protected $fillable = ["id", "company_id","product_id", "subscription_id", "stripe_product_id", "stripe_price_id", "current_plan_features", "start_date", "expiry_date", "remove_date", "status", "type", "created_at", "updated_at"];
    protected $presenter = AddonsHistoryPresenter::class;
    protected $table = 'addons_histories';
    protected $casts = [
        'current_plan_features' => 'array',
    ];
    public function scopeInsertOrUpdateCustomize($query,$data = []) {
        $response = [];
        foreach ($data as $key => $value) {
            $history = $query->where([
                'company_id' => $value['company_id'],
                'subscription_id' => $value['subscription_id'],
                'stripe_price_id' => $value['stripe_price_id'],
            ])->first();
            if(empty($history)) {
                $history = new AddonsHistory();
            }

            foreach ($value as $key2 => $val) {
                $history->{$key2} = $val;
            }
            $history->save();
            $response[] = $history;
        }
        return  $response;
    }

    public function addonsRemoval($company_initial,$date = null) {

        $date = $date ? $date : date('Y-m-d H:i:s');
        set_time_limit(0);
        $company_db = Masterdb::connect_company_db($company_initial);
        if ( $company_db == false ) {
            return true;
        }
        $getCompany = $this->getCompany();
        $timezone = $getCompany->timezone_id;

        // Convert provided $date (company timezone) to UTC
        $companyDateInUTC = Carbon::parse($date, $timezone)->timezone('UTC');

        $removed_addons_list = \DB::table('addons_histories')
            ->where('status', 0)
            ->where('expiry_date', '<=', $companyDateInUTC->format('Y-m-d H:i:s'))
            ->pluck('stripe_product_id')
            ->toArray();

            $companySubscriptions=(new StripeService())->getSubscription($getCompany->subscription_id);
            $subscriptionAddons=isset($companySubscriptions['metadata']['addons']) ? explode(',',$companySubscriptions['metadata']['addons']): [];

            $removed_addons_list =implode(',',array_diff($subscriptionAddons, $removed_addons_list));

            $data=[
                "metadata" => [
                                    "addons" => $removed_addons_list
                                ],
            ];
            (new StripeService())->updateSubscription($getCompany->subscription_id,$data);
            \DB::table('addons_histories')
            ->where('status', 0)
            ->where('expiry_date', '<=', $companyDateInUTC->format('Y-m-d H:i:s'))
            ->delete();


            $company_db = Masterdb::connect_master_db();
            if ( $company_db == false ) {
                return true;
            }
            \DB::table('addons_histories')
            ->where('status', 0)
            ->where('company_id', $getCompany->id)
            ->where('expiry_date', '<=', $companyDateInUTC->format('Y-m-d H:i:s'))
            ->delete();
            return true;
    }
    public function getCompany()
    {
        $db = \DB::table('companies')->first();
        if ( !empty($db) ) {
            return $db;
        }
        return NULL;
    }
}
