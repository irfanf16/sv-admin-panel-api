<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Historable;
use Laracasts\Presenter\PresentableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Presenters\ProductPresenter;
use App\Models\Company;
class Products extends Model
{

    use Historable;
    use PresentableTrait;
    protected $fillable = ["id", "stripe_id","product"];
    protected $presenter = ProductPresenter::class;
    protected $casts = [
        'product' => 'array',
    ];

    private $prefix = '';
    public function __construct() {
        $this->prefix = config('database.connections.'.config('database.default').'.prefix');
    }
    public function scopeGetProducts($query, $params) {

        $query->select('product');
        $query->selectRaw('GROUP_CONCAT(distinct '.$this->prefix.'companies.id SEPARATOR ",") as `company_ids`,
        GROUP_CONCAT(distinct '.$this->prefix.'companies_users.user_id) as user_ids,
        JSON_EXTRACT(product, "$.prices[*].active") as pricesstatus ,sort_by' );

        if(isset($params['search']))
        {
            $search = $params['search'];
            //unlink search param
            unset($params['search']);
        }
        if(!empty($params)) {
            $query->whereJsonContains('product', $params);
        }
        if(!empty($search)){
            $query->whereRaw("LOWER(JSON_UNQUOTE(JSON_EXTRACT(product, '$.name'))) LIKE ?", ['%' . strtolower($search) . '%']);
        }

        $query->leftjoin('companies', 'plan_id', '=', 'product->id');
        $query->leftjoin('companies_users', 'company_id', '=', 'companies.id');

        if(isset($params["fields"])) {
            $query->groupBy($params["fields"]);
        } else {
            $query->groupBy(['product','sort_by']);
        }

        return $query;
    }

    public function companies(): HasMany{
        return $this->hasMany(Company::class, 'plan_id','product->id');
    }

    public function scopeGetSubscriptionProducts($query, $params=[]) {
        if(!isset($params["fields"])) {
            $params["fields"] = [
                'products.stripe_id',
                'products.product',
            ];
        }
        $query = $query->select($params["fields"])
            ->join('companies', 'companies.plan_id', '=', 'products.stripe_id')
            ->where("companies.plan_staus", "=", "active")
            ->groupBy($params["fields"]);
        return $query;
    }

    public function scopeGetCurrentPackage($query, $company_id = 0) {
        $fields = [
            'companies.id', 'companies.title','companies.company_initial','companies.price_id',
            'companies.subscription_id','users.id as user_id','users.stripe_customer_id as stripe_customer_id',
            'users.email','users.first_name','users.last_name', 'companies.price_id', 'products.product',
            'subscriptions.subscription_id as old_subscription_id',
            'subscriptions.plan_id as old_plan_id',
            'subscriptions.price_id as old_price_id',
            'subscriptions.plan_staus as old_plan_staus',
            'subscriptions.plan_expiry as old_plan_expiry',
        ];
        $query = $query->select($fields)
            ->join('companies', 'companies.plan_id', '=', 'products.stripe_id')
            ->leftjoin('subscriptions', 'subscriptions.company_id', '=', 'companies.id')
            ->join('users', 'companies.super_admin_id', '=', 'users.id')
            ->where('companies.id', $company_id);
        return $query;
    }

    public function updateProductStatus($request): array
    {
        $type = $request->type;
        $stripe_id = $request->plan_id;
        $status = $request->status;
        //update prices active flag to false in local database
        if($stripe_id && $type == "plans"){
            $product = Products::where('stripe_id', $stripe_id)->whereJsonContains('product->metadata->type',trim($type))->first();
            $productData = $product->product;

            $productData['metadata']['public'] = (string)$status;
            $product->setAttribute('product', $productData);
            $product->save();
            return ['status' => 'success', 'product' => $product];
        }
        return ['status' => 'error', 'message' => 'product update failed'];
    }

    public function featurePlans($featureIds = []){
        return count($featureIds) > 0 ? $this->selectRaw('stripe_id, JSON_UNQUOTE(JSON_EXTRACT(product, "$.metadata.module_features_list")) as features')
                ->whereRaw(
                    implode(' OR ', array_map(fn($featureId) => "JSON_UNQUOTE(JSON_EXTRACT(product, '$.metadata.module_features_list')) LIKE CONCAT('%', ?, '%')", $featureIds)),
                    $featureIds
                )
                ->get()
                ->flatMap(function ($product) {
                    // Convert the comma-separated list into an array and map it to include the stripe_id
                    return collect(explode(',', $product->features))->filter()->map(function ($featureId) use ($product) {
                        return [
                            'feature_id' => (int)$featureId,
                            'stripe_id' => $product->stripe_id
                        ];
                    });
                })
                ->whereIn('feature_id', $featureIds)
                ->groupBy('feature_id') : [];
    }
}
