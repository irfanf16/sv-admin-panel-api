<?php

namespace App\Services;

use App\Models\Products;

// use Carbon\Carbon;
use App\Models\Settings\Email;
use App\Models\UserTableMaster;
use App\Models\Company;
use App\Models\ModuleFeatureList;
use App\Models\AddonsHistory;
use App\Libraries\Masterdb;
use App\Models\User;
use DB;
use Illuminate\Support\Facades\Http;
use Database\Seeders\ModuleSeeder;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class StripeService
{

    private $stripe = null;
    private $limit = 100;
    private $api = 'https://api.stripe.com/v1';

    public function __construct()
    {
        $this->stripe = new \Stripe\StripeClient([
            'api_key' => $this->getPrivateKey(),
            'stripe_version' => '2023-10-16'
        ]);
    }

    public function getPrivateKey()
    {
        // return 'sk_test_51LA9o4GN1EGOh1ni2sghY7ntxD7iKreSh7pu9LgZglbSXBJbnIVHafmUmAfj7T0p7y9Z87FW2zLbw5vDevFJbUTD00rfNZAxMS';
        return getenv('STRIPE_PRIVATE_KEY');
    }

    public function coupons()
    {
        $allCoupons = [];
        $coupons = $this->stripe->coupons->all(['limit' => $this->limit]);
        foreach ($coupons->autoPagingIterator() as $key => $coupon) {
            $allCoupons[] = $coupon;
        }
        return $allCoupons;
    }

    public function coupon($id)
    {
        return $this->stripe->coupons->retrieve($id, []);
    }

    public function deleteCoupon($id)
    {
        return $this->stripe->coupons->delete($id, []);
    }

    public function createCoupon($request)
    {
        $couponData = $request->all();
        $couponData['redeem_by'] = strtotime($request->redeem_by);
        if ($couponData['duration'] != 'repeating') {
            unset($couponData['duration_in_months']);
        }
        if ($request->percent_off == true) {
            unset($couponData['amount_off']);
            $couponData['percent_off'] = $request->amount_off;
        }
        return $this->stripe->coupons->create($couponData);

    }

    public function updateCoupon($request, $id = '')
    {
        return $this->stripe->coupons->update($id, $request->all());
    }

    public function products()
    {
        $allProducts = [];
        $products = $this->stripe->products->all(['limit' => $this->limit]);
        foreach ($products->autoPagingIterator() as $key => $product) {
            $allProducts[] = $product->toArray();
        }
        return ['product' => $allProducts, 'prices' => []];
    }

    public function searchProducts($search = [])
    {
        if (empty($search)) {
            $search['query'] = "active:'true' AND metadata['type']:'addons'";
        }
        $search['limit'] = $this->limit;
        $allProducts = [];
        $products = $this->stripe->products->search($search);
        foreach ($products->autoPagingIterator() as $key => $product) {
            $productArray = $product->toArray();
            $productArray['price'] = [];
            $allProducts[] = $productArray;
        }
        return $allProducts;
    }

    public function createProduct($post)
    {
        $prices = [];
        if (isset($post['prices']) && !empty($post['prices'])) {
            $prices = $post['prices'];
            unset($post['prices']);
        }
        if (empty($post['description'])) {
            unset($post['description']);
        }

        $product = $this->stripe->products->create($post);
        $stripPrices = [];
        if (!empty($prices)) {
            foreach ($prices as $key => $price) {
                if (isset($price['recurring']) && $price['recurring'] == 'bi-annually') {
                    $price['recurring'] = 'month';
                    $price['interval_count'] = 6;
                }
                if (isset($price['recurring']) && $price['recurring'] == 'quarterly') {
                    $price['recurring'] = 'month';
                    $price['interval_count'] = 3;
                }
                $stripPrices[] = $this->createPrice($product->id, $price)->toArray();
            }
        }
        return ['product' => $product->toArray(), 'prices' => $stripPrices];
    }

    public function product($id, $price = true)
    {
        $product = $this->stripe->products->retrieve($id, []);
        $stripPrices = [];
        if ($price) {
            $stripPrices = $this->searchPrices(['query' => 'product:"' . $id . '" AND active:"true"']);
        }
        return ['product' => $product->toArray(), 'prices' => $stripPrices];
    }

    public function updateProduct($post = [], $id = '')
    {
        $prices = [];
        if (isset($post['stripe_id'])) {
            $id = $post['stripe_id'];
            unset($post['stripe_id']);
        }
        if (isset($post['prices']) && !empty($post['prices'])) {
            $prices = $post['prices'];
            unset($post['prices']);
        }
        if (empty($post['description'])) {
            unset($post['description']);
        }
        $product = $this->stripe->products->update($id, $post);
        $stripPrices = [];
        if (!empty($prices)) {
            foreach ($prices as $key => $price) {
                if (isset($price['recurring']) && $price['recurring'] == 'bi-annually') {
                    $price['recurring'] = 'month';
                    $price['interval_count'] = 6;
                }
                if (isset($price['recurring']) && $price['recurring'] == 'quarterly') {
                    $price['recurring'] = 'month';
                    $price['interval_count'] = 3;
                }
                if (isset($price['id'])) {
                    $stripPrices[] = $this->updatePrice($price['id'], $price)->toArray();
                } else {
                    $stripPrices[] = $this->createPrice($id, $price)->toArray();
                }
            }
        }
        return ['product' => $product->toArray(), 'prices' => $stripPrices];
    }

    public function deleteProduct($id)
    {
        try {
            $prices = $this->searchPrices(['query' => "active:'false' AND product:'" . $id . "'"]);
            if (!empty($prices)) {
                foreach ($prices as $key => $price) {
                    try {
                        $this->deletePrice($price->id);
                    } catch (\Throwable $th) {
                        //throw $th;
                    }
                }
            }
            // Fist Try to hard delete. If not successfull then we'll archive the product.
            return $this->stripe->products->delete($id, []);
        } catch (\Throwable $th) {
            return $this->stripe->products->update($id, ['active' => false]);
        }
    }

    public function createPrice($product_id, $price)
    {
        return $this->stripe->prices->create($this->priceDateUpdate($price, $product_id));
    }

    public function updatePrice($price_id, $price)
    {
        return $this->stripe->prices->update($price_id, $this->priceDateUpdate($price));
    }

    public function getPriceByid($price_id)
    {
        return $this->stripe->prices->retrieve($price_id);
    }

    private function priceDateUpdate($price, $product_id = '')
    {
        $calculated_price = number_format($price['unit_amount'] * 100,2, '.', '');
        if(!is_null($calculated_price)){
            $parts = explode('.',$calculated_price);
            if($parts[1] != null && !($parts[1] > 0)){
                $calculated_price = $parts[0];
            } else {
                $calculated_price = $parts[0];
            }
        }

        $post = [
            'currency' => isset($price['currency']) ? $price['currency'] : 'usd',
            'unit_amount' => $calculated_price, // Note Stipe is taking flat_amount as a decimail. So we are multiplying to 100
            'metadata' => isset($price['metadata']) ? $price['metadata'] : [],
            'nickname' => isset($price['nickname']) ? $price['nickname'] : '',
            'billing_scheme' => isset($price['billing_scheme']) ? $price['billing_scheme'] : 'per_unit',
        ];
//        dump($post);
        if (isset($price['recurring']) && !empty($price['recurring'])) {
            if (isset($price['interval_count'])) {
                $post['recurring'] = [
                    'interval' => $price['recurring'],
                    'interval_count' => $price['interval_count'],
                ];
            } else {
                $post['recurring'] = ['interval' => $price['recurring']];
            }
        }
        if (isset($price['id'])) {
            unset($post['billing_scheme']);
            unset($post['unit_amount']);
            unset($post['currency']);
            unset($post['recurring']);
        }
        if (!empty($product_id)) {
            $post['product'] = $product_id;
        }
        if (isset($post['billing_scheme']) && $post['billing_scheme'] == 'tiered') {
            $post['tiers'] = isset($price['tiers']) ? $price['tiers'] : [];
            foreach ($post['tiers'] as $key => $tier) {
                // Note Stipe is taking flat_amount as a decimail. So we are multiplying to 100
                if ($tier['flat_amount'] != 'inf') {
                    $post['tiers'][$key]['flat_amount'] = $tier['flat_amount'] * 100;
                }
            }
            // tiered does not support unit ammount.
            unset($post['unit_amount']);
            $post['tiers_mode'] = $price['tiers_mode'];
        }

        return $post;
    }

    public function prices()
    {
        $allPrices = [];
        $prices = $this->stripe->prices->all(['limit' => $this->limit]);
        foreach ($prices->autoPagingIterator() as $key => $price) {
            $allPrices[] = $price;
        }
        return $allPrices;
    }

    public function searchPrices($search = '')
    {
        $allPrices = [];
        if (empty($search)) {
            $search['query'] = "active:'true'";
        }
        $search['limit'] = $this->limit;
        $prices = $this->stripe->prices->search($search);
        foreach ($prices->autoPagingIterator() as $key => $price) {
            $allPrices[] = $price;
        }
        return $allPrices;
    }

    public function retrievePrice($price_id, $details = [])
    {
        return $this->stripe->prices->retrieve($price_id, $details);
    }

    public function deletePrice($price_id, $stripe_id = null)
    {
        $results = [];
        //api updated so comma separated ids string can be passed for multiple prices deletion
        if (!empty($price_id)) {
            $prices = explode(',', $price_id);
            foreach ($prices as $price_id) {
                // Note: Stripe does not support price delete action. Only way to delete the price is just update active to false;
                $results[] = $this->stripe->prices->update(trim($price_id), ['active' => false]);

            }
            //update prices active flag to false in local database
            if ($stripe_id) {
                $products = Products::where('stripe_id', $stripe_id)->get();

                foreach ($products as $product) {
                    $productData = $product->product;
                    $prices = $productData['prices'];
                    foreach ($prices as &$price) {
                        $price['active'] = false;
                    }
                    $productData['prices'] = $prices;
                    $product->setAttribute('product', $productData);
                    $product->save();
                }
            }

        }
        return $results;
    }

    // Customers
    public function searchCustomers($search = [])
    {
        $customer_array = [];
        $email = $search['email'];
        $customer = $this->stripe->customers->search(['limit' => $this->limit, 'query' => "email~'" . $email . "'"]);
        $customers = $customer->toArray();
        if (!empty($customers['data'])) {
            $key = array_search($email, array_column($customers['data'], 'email'));
            if ($key !== false) {
                $customer_array = $customers['data'][$key];
            }
        }
        return $customer_array;
    }

    private function customerData($data)
    {
        // $post = [
        //     'name' => isset($data['name']) ? $data['name'] : '',
        //     'email' => isset($data['email']) ? $data['email'] : '',
        //     'phone' => isset($data['phone']) ? $data['phone'] : '',
        //     'description' => isset($data['email']) ? $data['email'] : '',
        //     'metadata' => isset($data['metadata']) ? $data['metadata'] : [],
        //     'coupon' => isset($data['coupon']) ? $data['coupon'] : '',
        // ];
        // if(isset($data['name'])) {
        //     $post['name'] = $data['name'];
        // }
        // if(isset($data['invoice_settings'])) {
        //     $post['invoice_settings'] = $data['invoice_settings'];
        // }
        $post = $data;
        return $post;
    }

    public function upsertCustomer($email, $data = [])
    {

        $customer = $this->stripe->customers->search(['limit' => $this->limit, 'query' => "email~'" . $email . "'"]);
        $customers = $customer->toArray();
        $customer = [];
        if (empty($customers['data'])) {
            $customer = $this->createCustomer($data);
        } else {
            $key = array_search($email, array_column($customers['data'], 'email'));
            if ($key !== false) {
                $customer_id = $customers['data'][$key]['id'];
                $customer = $this->updateCustomer($customer_id, $data);
            }
        }
        return $customer;
    }

    public function createCustomer($data = [])
    {
        $customer = $this->stripe->customers->create($this->customerData($data));
        $user = UserTableMaster::where("email", $customer->email)->first();
        if (!empty($user)) {
            $user->stripe_customer_id = $customer->id;
            $user->save();
        }
        return $customer;
    }

    public function updateCustomer($customer_id, $data = [])
    {
        $customer = $this->stripe->customers->update($customer_id, $this->customerData($data));
        $user = UserTableMaster::where("email", $customer->email)->first();
        if (!empty($user)) {
            $user->stripe_customer_id = $customer->id;
            $user->save();
        }
        return $customer;
    }

    public function deleteCustomer($customer_id)
    {
        $customer = $this->stripe->customers->delete($customer_id, []);
        if ($customer->deleted == true) {
            $user = UserTableMaster::where("stripe_customer_id", $customer_id)->first();
            if (!empty($user)) {
                $user->stripe_customer_id = "";
                $user->save();
            }
        }
        return $customer;
    }

    public function retrieveCustomer($customer_id, $params = [])
    {
        return $this->stripe->customers->retrieve($customer_id, $params);
    }

    public function customers()
    {
        $allCustomers = [];
        $customers = $this->stripe->customers->all(['limit' => $this->limit]);
        foreach ($customers->autoPagingIterator() as $key => $customer) {
            $allCustomers[] = $customer;
        }
        return $allCustomers;
    }


    // Payment
    public function setupIntents($data = [], $id = 0)
    {
        if ($id > 0) {
            return $this->stripe->setupIntents->update($id, [
                'metadata' => [
                    'company_id' => isset($data['company_id']) ? $data['company_id'] : '',
                ],
                'payment_method_types' => ['card'],
            ]);
        } else {
            return $this->stripe->setupIntents->create([
                'customer' => $data['customer_id'],
                'payment_method_types' => ['card'],
                'metadata' => [
                    'company_id' => isset($data['company_id']) ? $data['company_id'] : '',
                ],
            ]);
        }
    }

    public function setupIntentsConfirm($data = [])
    {
        // $setupPaymentIntent = $this->stripe->setupIntents->confirm($data['id'],['payment_method' => 'pm_card_visa']);
        $setupPaymentIntent = $this->stripe->setupIntents->retrieve($data['id']);
        $customerNewUpdate = $this->stripe->customers->update($data['stripe_customer_id'], [
            'invoice_settings' => [
                'default_payment_method' => $setupPaymentIntent->payment_method,
            ]
        ]);
        return [
            'setupPaymentIntent' => $setupPaymentIntent,
            'customerNewUpdate' => $customerNewUpdate,
        ];
    }

    public function createSubscription($data = [], $subscription_id = '')
    {
        // Unsubscribe old plan
        $old_items = [];
        $old_subscription = [];
        $old_subscription['metadata']['module_features_list'] = '';
        $old_subscription['metadata']['modules'] = '';
        $old_addons = [];
        if (!empty($subscription_id)) {
            $old_subscription = $this->getSubscription($subscription_id)->toArray();
            $old_items = [];
            foreach ($old_subscription['items']['data'] as $key => $item) {
                $old_items[] = [
                    'id' => $item['id'],
                    'deleted' => true,
                ];
            }
            $data['company_id'] = $old_subscription['metadata']['company_id'];
            $data['stripe_customer_id'] = $old_subscription['customer'];
            if(isset($old_subscription['metadata']['addons']) && !empty($old_subscription['metadata']['addons'])) {
                $old_addons = explode(',', $old_subscription['metadata']['addons']);
            }
        }

        $price_ids = $data['price_ids'];
        $customer_id = $data['stripe_customer_id'];
        $company_id = $data['company_id'];
        $user_id = $data['user_id'];
        $closure_plan = isset($data['closure_plan']) ? $data['closure_plan'] : 0;
        $payment_status = isset($data['payment_status']) ? $data['payment_status'] : null;
        $free_addons = [];
        $items = [];
        $quantity = 1;
        $invoiceItems = [];
        $trial_period_days = 0;
        $metadata = [];
        $plan_id = '';
        $module_features_list = [];
        $module_list = [];
        $plan_price_id = null;
        $discounts = isset($data['discounts']) ? $data['discounts'] : [];
        foreach ($price_ids as $key => $price_id) {
            $productInstance = Products::getProducts([
                'prices' => [
                    'id' => $price_id,
                ]
            ])->first();
            // $productInstance = [];// for testing

            if (!empty($productInstance)) {
                $product = $productInstance->product;
                foreach ($product['prices'] as $key => $price) {
                    if ($price['id'] == $price_id) {
                        break;
                    }
                }
            } else {
                // in case someone delete the price accedently from db, we'll search the price on stripe
                $price = $this->getPriceByid($price_id)->toArray();
                $product = $this->product($price['product'], false);
                if (!empty($product)) {
                    $product = $product['product'];
                }
            }
            // dd($product);
            if (isset($product['metadata']['module_features_list'])) {
                $module_features_list = array_merge($module_features_list, explode(',', $product['metadata']['module_features_list']));
            }

            if (isset($product['metadata']['modules'])) {
                $module_list = array_merge($module_list, explode(',', $product['metadata']['modules']));
            }

            if (!isset($price['recurring'])) {
                $invoiceItems[] = ['price' => $price_id];
                continue;
            }
            if (isset($product['metadata']['type']) && $product['metadata']['type'] == 'plans') {
                $metadata = $product['metadata'];
                $plan_id = $product['id'];
                $metadata['company_id'] = $company_id;
                $metadata['plan_id'] = $plan_id;
                $metadata['price_id'] = $plan_price_id = $price_id;
            }
            if (isset($product['metadata']['minimum_users'])) {
                $quantity = $product['metadata']['minimum_users'];
            }
            if (isset($product['metadata']['addons'])) {
                $free_addons = explode(",", $product['metadata']['addons']);
            }
            if (isset($product['metadata']['trial_period_days']) && $product['metadata']['trial_period_days'] > 0) {
                $trial_period_days = (int)$product['metadata']['trial_period_days'];
            }

            $items[] = [
                'price' => $price_id,
                'quantity' => $quantity,
            ];
            if (!empty($free_addons)) {
                foreach ($free_addons as $key => $free_addon) {
                    $items[] = [
                        'price_data' => [
                            'currency' => 'usd',
                            'product' => $free_addon,
                            'unit_amount' => 0,
                            'recurring' => [
                                'interval' => $price['recurring']['interval'],
                                'interval_count' => $price['recurring']['interval_count'],
                            ],
                        ],
                        'quantity' => $quantity,
                    ];
                }
            }

        }
        // let's subscribe the customer
        $sendData = [
            'customer' => $customer_id,
            'items' => array_merge($items, $old_items),
            'metadata' => $metadata,
        ];
        if (!empty($invoiceItems)) {
            $sendData['add_invoice_items'] = $invoiceItems;
        }

        if ($trial_period_days == 0) {
            $sendData['trial_end'] = 'now';
        } else {
            $sendData['trial_end'] = strtotime("+$trial_period_days day");
        }
        $sendData["enable_incomplete_payments"] = false;
        if ($closure_plan == 1) {
            unset($sendData['trial_end']); // No need for the trial period , if we'hv closure plane.
            unset($sendData["enable_incomplete_payments"]);
        }

        $sendData['metadata']['module_features_list'] = implode(',', $module_features_list);
        $sendData['discounts'] = $discounts;

        $system_features = ModuleFeatureList::getFeaturesList($module_features_list)->getType(2)->get();

        // dd($subscription_id, $sendData);
        if (!empty($subscription_id)) {
            unset($sendData['customer']);
            unset($sendData['trial_end']);
            if(!empty($old_addons)) {
                $metadataAddons = array_merge($old_addons, $free_addons);
                $metadataAddons = array_unique($metadataAddons);
                $sendData['metadata']['addons'] = implode(",", $metadataAddons);
            }
            $sendData['billing_cycle_anchor']= 'now';
            $subscription = $this->stripe->subscriptions->update($subscription_id, $sendData);
        } else {
            // $nextMonthFirst = new \DateTime('first day of next month midnight');
            // $sendData['billing_cycle_anchor'] = $nextMonthFirst->getTimestamp();
            $subscription = $this->stripe->subscriptions->create($sendData);
        }
        $plan_users = $subscription->metadata->minimum_users;
        $company = Company::where(['id' => $company_id, 'super_admin_id' => $user_id])->first();
        if (!empty($company)) {
            try {
                $company->subscription_id = $subscription->id;
                $company->plan_id = $plan_id;
                $company->plan_expiry = date('Y-m-d H:i:s', $subscription->current_period_end);
                $company->plan_staus = $subscription->status;
                $company->price_id = $plan_price_id;
                $company->closure_plan = $closure_plan;
                $company->payment_status = $payment_status;
                $company->plan_users = $plan_users;
                $company->save();
                    // If no exception, the save was successful
            } catch (\Exception $e) {
                // Handle the exception
            }
        }
        if (empty($subscription_id) && $trial_period_days > 0 && !$closure_plan) {
            try {
                $user = UserTableMaster::where(['id' => $user_id])->first();

                //fetch email body based on these conditions from database
//                $emailConditions = ['service' => 'trial', 'trial' => 'start', 'status' => 1, 'days' => '0'];
                $emailConditions = ['service' => 'subscription', 'subscription' => 'subscription', 'status' => 1, 'days' => '0'];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();

                if (is_null($email)) {
                    Log::debug("START_TRIAL_EMAIL_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());
                }

                //Log::debug("START_TRIAL_EMAIL_QUERY_RESULT: " . $email != null ? json_encode($email->toArray()) : null);

                $name = $user->first_name . ' ' . $user->last_name;
                /**
                 * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                 * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                 */
                $search = ['[x_name]'];
                $replace = [$name];
                $this->sendMail($search, $replace, $user_id, $user->email, $email);
            } catch (\Exception $e) {
                Log::debug("START_TRIAL_EMAIL_ERROR: " . $e->getMessage());
                Log::debug("START_TRIAL_EMAIL_ERROR_LINE: " . $e->getLine());
            }
//            dispatch(new \App\Jobs\StartTrialJob($company, $user));
        }


        //trigger email if trial days equals to zero
        Log::debug("PLAN_WITH_NO_TRIAL_SUBSCRIPTION_ID: " . $subscription_id);
        Log::debug("PLAN_WITH_NO_TRIAL_DAYS: " . $trial_period_days);
        if (empty($subscription_id) && $trial_period_days == 0 && !$closure_plan) {
            try {
                if (empty($user)) {
                    $user = UserTableMaster::where(['id' => $user_id])->first();
                }
                //fetch email body based on these conditions from database
//                $emailConditions = ['service' => 'active_plan', 'active_plan' => 'payment_successful', 'status' => 1, 'days' => '0'];
                $emailConditions = ['service' => 'subscription', 'subscription' => 'subscription', 'status' => 1, 'days' => '0'];
                $email = Email::where($emailConditions)->whereNull('deleted_at')->first();
                if (is_null($email)) {
                    Log::debug("PLAN_WITH_NO_TRIAL_RAW_QUERY: " . Email::where($emailConditions)->whereNull('deleted_at')->toRawSql());
                }
                Log::debug("PLAN_WITH_NO_TRIAL_RAW_QUERY: " . $email != null ? $email->id : null);
                $name = $user->first_name . ' ' . $user->last_name;
                /**
                 * $search array [[x_days], ...] represent value which we need to find in email body e.g [x_days] will be replaced with name
                 * $replace array [[$name], ...] contains values which will be replaced with search values like "[x_days]" will be replaced with $name
                 */
                $search = ['[x_name]'];
                $replace = [$name];
                $this->sendMail($search, $replace, $user_id, $user->email, $email);
            } catch (\Exception $e) {
                Log::debug("PLAN_WITH_NO_TRIAL_ERROR: " . $e->getMessage());
                Log::debug("PLAN_WITH_NO_TRIAL_ERROR_LINE: " . $e->getLine());
            }
        }


        $subscription->plan_id = $plan_id;
        $subscription->system_features = $system_features;
        if (!empty($old_subscription)) {
            $old_system_features_ids = explode(',', isset($old_subscription['metadata']['module_features_list']) ? $old_subscription['metadata']['module_features_list'] : '');
            if (!empty($old_system_features_ids)) {
                $old_system_features = ModuleFeatureList::getFeaturesList($old_system_features_ids)->getType(2)->get();
            } else {
                $old_system_features = [];
            }
        }
        try {
            // Updating child database.
            Masterdb::connect_company_db($company->company_initial);
            if ($closure_plan == 0) {
                // remove old subscription plan feature ids from the database.
                if (!empty($old_items)) {
                    foreach ($old_system_features as $key => $system_feature) {
                        DB::table("system_features")
                            ->where('feature_key', $system_feature['feature_key'])
                            ->where('parent_module_id', $system_feature['parent_module_id'])
                            ->where('sub_module_id', $system_feature['sub_module_id'])
                            ->update([
                                    'status' => 0,
                                    'package_id' => $plan_id,
                                    'feature_value' => $system_feature['feature_value']
                                ]
                            );
                    }

                    $old_modules = explode(',', $old_subscription['metadata']['modules']);

                    if (!empty($old_modules)) {
                        DB::table("modules")->whereIn('id', $old_modules)->update(['status' => 0]);
                    }
                }
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

            $updates_depend_status = [];
            if (!empty($module_list)) {
                DB::table("modules")->where('id', 81)->update(['status' => 1]);
                DB::table("modules")->whereIn('id', $module_list)->update(['status' => 1]);

                $modules_all = (new ModuleSeeder())->getModules();
                foreach ($modules_all as $key => $module) {
                    if ($module['dependent_module_id'] != 0) {
                        if (in_array($module['dependent_module_id'], $module_list)) {
                            $updates_depend_status[] = $module['id'];
                        }
                    }
                }
                if (is_array($updates_depend_status) && count($updates_depend_status) > 0) {
                    DB::table("modules")->whereIn('id', $updates_depend_status)->update(['status' => 1]);
                    DB::table("modules")->where('id', 78)->update(['status' => 1]);
                }
            }

            DB::table("companies")->update([
                'subscription_id' => $subscription->id,
                'plan_id' => $plan_id,
                'plan_expiry' => date('Y-m-d H:i:s', $subscription->current_period_end),
                'plan_staus' => $subscription->status,
                'price_id' => $plan_price_id,
                "closure_plan" => $closure_plan,
                "plan_users" => $plan_users,
            ]);

        } catch (\Exception $ex) {
            //throw $th;
        }

        // End Updating child database.
        return $subscription;
    }

    public function updateSubscriptionQuantity($id, $data)
    {
        // $payment_intent = $this->getPaymentIntents('pi_3PbgahGN1EGOh1ni0mfHgeQV')->toArray();
        $subscription = $this->getSubscription($id)->toArray();
        $updateItems = [];
        $quantity = 0;
        foreach ($subscription['items']['data'] as $key => $item) {
            $quantity = $item['quantity'] + (int)$data['quantity'];
            $updateItems[] = [
                'id' => $item['id'],
                'quantity' => $quantity,
            ];
        }
        if (!empty($updateItems)) {
            $sendData = [
                'items' => $updateItems,
            ];
            $sendData["enable_incomplete_payments"] = false;
            $sendData["off_session"] = true;
            if (isset($subscription['metadata']['proration']) && $subscription['metadata']['proration'] == 1) {
                $sendData["proration_behavior"] = 'always_invoice';
            } else {
                $sendData["proration_behavior"] = 'none';
            }
            return $this->stripe->subscriptions->update($id, $sendData)->toArray();
        }
        return false;
    }
    public function removeSubscriptionItems($id, $data) {
        $addAddonsHistory = [];
        $subscriptionRaw = $this->getSubscription($id);
        $oldSubscription = $subscriptionRaw->toArray();
        $company_id = $oldSubscription['metadata']['company_id'];
        $start_date = date('Y-m-d H:i:s', $oldSubscription['current_period_start']);
        $expiry_date = date('Y-m-d H:i:s', $oldSubscription['current_period_end']);
        $data['items'] = [];
        foreach ($data['remove_addons'] as $key => $remove_addon) {
            $price = $this->retrievePrice($remove_addon["price"])->toArray();
            $product_id = $price['product'];
            $product = $this->product($product_id);
            if(empty($price["recurring"])) {
                $date = new \DateTime();
                $date->modify('+100 years');
                $addAddonsHistory[$key]['expiry_date'] = $date->format('Y-m-d H:i:s');
                $addAddonsHistory[$key]['type'] = 'one-time';
            } else {
                $addAddonsHistory[$key]['type'] = 'recurring';
                $addAddonsHistory[$key]['expiry_date'] = $expiry_date;
            }
            // Addons History Table.
            $addAddonsHistory[$key]['company_id'] = $company_id;
            $addAddonsHistory[$key]['stripe_product_id'] = $product_id;
            $addAddonsHistory[$key]['stripe_price_id'] = $remove_addon["price"];
            $addAddonsHistory[$key]['subscription_id'] = $id;
            $addAddonsHistory[$key]['status'] = 0;
            $addAddonsHistory[$key]['start_date'] = $start_date;
            $addAddonsHistory[$key]['current_plan_features']['module_features_list'] = (isset($product['product']['metadata']['module_features_list']) && !empty($product['product']['metadata']['module_features_list'])) ? explode(',',$product['product']['metadata']['module_features_list']) : [];
            $addAddonsHistory[$key]['current_plan_features']['modules'] = (isset($product['product']['metadata']['modules']) && !empty($product['product']['metadata']['modules'])) ? explode(',',$product['product']['metadata']['modules']) : [];
            
            // Items to remove
            foreach ($oldSubscription['items']['data'] as $key => $item) {
                if ($item['price']['id'] == $remove_addon['price']) {
                    $data['items'][] = [
                        'id' => $item['id'],
                        'deleted' => true,
                    ];
                }
            }
        }
       
        $subscription = null;
        if(!empty($data['items'])) {
            unset($data['remove_addons']);
            $subscription = $this->stripe->subscriptions->update($id, $data);
        }
        if(!empty($addAddonsHistory)) {
            $p_ids = [];
            foreach ($addAddonsHistory as $key => $value) {
                $p_ids[] = $value['stripe_product_id'];
            }
            $databaseProducts = Products::wherein('stripe_id', $p_ids)->get();
            foreach ($addAddonsHistory as $key => $value) {
                foreach ($databaseProducts as $key2 => $databaseProduct) {
                    if($databaseProduct->stripe_id == $value['stripe_product_id']) {
                        $addAddonsHistory[$key]['product_id'] = $databaseProduct->id;
                    }
                }
            }
            $addHistoryData = AddonsHistory::insertOrUpdateCustomize($addAddonsHistory);
        }
        return [
            'subscription' => $subscription,
            'oldSubscription' => $oldSubscription,
            'addHistoryData' => $addHistoryData,
        ];
    }
    public function updateSubscription($id, $data)
    {
        $addAddonsHistory = [];
        $invoiceItems = [];
        $subscriptionItems = [];
        $addHistoryData = [];
        if (isset($data['add_invoice_items']) && !empty($data['add_invoice_items'])) {
            $addons = [];
            $subscriptionRaw = $this->getSubscription($id);
            $subscription = $subscriptionRaw->toArray();
            $company_id = $subscription['metadata']['company_id'];
            $addons = isset($subscription['metadata']["addons"]) ? explode(',', $subscription['metadata']["addons"]) : [];
            $start_date = date('Y-m-d H:i:s', $subscription['current_period_start']);
            $expiry_date = date('Y-m-d H:i:s', $subscription['current_period_end']);
           
            foreach ($data['add_invoice_items'] as $key => $add_invoice_item) {
                $price = $this->retrievePrice($add_invoice_item["price"])->toArray();
                $product_id = $price['product'];
                $product = $this->product($product_id);
                $data["enable_incomplete_payments"] = false;
                $data["off_session"] = true;
                if (isset($product['metadata']['proration']) && $product['metadata']['proration'] == 1) {
                    $data["proration_behavior"] = 'always_invoice';
                } else {
                    $data["proration_behavior"] = 'none';
                }

                if(empty($price["recurring"])) {
                    // For one time payment ivoice we'll recharge immediately.
                    $invoiceItems[] = $price;
                    unset($data['add_invoice_items'][$key]);

                    $date = new \DateTime();
                    $date->modify('+100 years');
                    $addAddonsHistory[$key]['expiry_date'] = $date->format('Y-m-d H:i:s');
                    $addAddonsHistory[$key]['type'] = 'one-time';
                } else {
                    $subscriptionItems[] = [
                        'price' => $add_invoice_item["price"] ,
                        'quantity' => $subscription['items']['data'][0]['quantity']
                    ];
                    unset($data['add_invoice_items'][$key]);

                    
                    $addAddonsHistory[$key]['type'] = 'recurring';
                    $addAddonsHistory[$key]['expiry_date'] = $expiry_date;
                }
                $addons[] = $product_id;
                // $addons[] = $add_invoice_item["price"];

                // Addons History Table.
                $addAddonsHistory[$key]['company_id'] = $company_id;
                $addAddonsHistory[$key]['product_id'] = 0;
                $addAddonsHistory[$key]['stripe_product_id'] = $product_id;
                $addAddonsHistory[$key]['stripe_price_id'] = $add_invoice_item["price"];
                $addAddonsHistory[$key]['subscription_id'] = $id;
                $addAddonsHistory[$key]['status'] = 1;
                $addAddonsHistory[$key]['start_date'] = $start_date;
                $addAddonsHistory[$key]['current_plan_features']['module_features_list'] = (isset($product['product']['metadata']['module_features_list']) && !empty($product['product']['metadata']['module_features_list'])) ? explode(',',$product['product']['metadata']['module_features_list']) : [];
                $addAddonsHistory[$key]['current_plan_features']['modules'] = (isset($product['product']['metadata']['modules']) && !empty($product['product']['metadata']['modules'])) ? explode(',',$product['product']['metadata']['modules']) : [];
            }
            $addons = array_unique($addons);
            $data['metadata']['addons'] = implode(',', $addons);
        }
        if(!empty($addAddonsHistory)) {
            $p_ids = [];
            foreach ($addAddonsHistory as $key => $value) {
                $p_ids[] = $value['stripe_product_id'];
            }
            $databaseProducts = Products::wherein('stripe_id', $p_ids)->get();
            foreach ($addAddonsHistory as $key => $value) {
                foreach ($databaseProducts as $key2 => $databaseProduct) {
                    if($databaseProduct->stripe_id == $value['stripe_product_id']) {
                        $addAddonsHistory[$key]['product_id'] = $databaseProduct->id;
                    }
                }
            }
            $addHistoryData = AddonsHistory::insertOrUpdateCustomize($addAddonsHistory);
        }
        
        if(!empty($invoiceItems)) {
            $invoice = $this->stripe->invoices->create([
                'customer' => $subscription["customer"],
                'collection_method'=>'charge_automatically',
                'subscription' => $id,
            ]);
            foreach ($invoiceItems as $key => $item) {
                $this->stripe->invoiceItems->create([
                    'customer' => $subscription["customer"],
                    'subscription' => $id,
                    'price' => $item['id'],
                    'invoice' => $invoice->id,
                ]);
            }
            $this->payInvoice($invoice->id);
        }
        if(!empty($subscriptionItems)) {
            foreach ($subscriptionItems as $key => $subscriptionItem) {
                $subscriptionRaw->items->create($subscriptionItem);
            }
            $invoice = $this->stripe->invoices->create([
                'customer' => $subscription["customer"],
                'collection_method'=>'charge_automatically',
                'subscription' => $id,
            ]);
            $this->payInvoice($invoice->id);
        }
        return [
            'subscription' => $this->stripe->subscriptions->update($id, $data),
            'addHistoryData' => $addHistoryData,
        ];
        // return $this->stripe->subscriptions->update($id, $data);
    }

    public function cancelSubscription($id)
    {
        return $this->stripe->subscriptions->cancel($id);
    }

    public function getSubscription($id, $query = '')
    {
        return $this->stripe->subscriptions->retrieve($id);
    }

    public function getCardsByCustomerId($customer_id)
    {
        return Http::withBasicAuth($this->getPrivateKey(), '')->get($this->api . '/customers/' . $customer_id . '/cards')->json();
    }

    public function getCardByCustomerIdCardId($customer_id, $card_id)
    {
        return Http::withBasicAuth($this->getPrivateKey(), '')->get($this->api . '/customers/' . $customer_id . '/cards/' . $card_id)->json();
    }

    public function getPaymentMethodsByCustomerId($customer_id)
    {
        return $this->stripe->customers->allPaymentMethods($customer_id, ['limit' => $this->limit]);
    }

    public function getPaymentMethodByCustomerIdPmID($payment_method_id)
    {
        return $this->stripe->paymentMethods->retrieve($payment_method_id);
    }

    public function paymentMethodDetach($payment_method_id)
    {
        return $this->stripe->paymentMethods->detach($payment_method_id, []);
    }

    public function getPaymentIntents($paymentIntent_id)
    {
        return $this->stripe->paymentIntents->retrieve($paymentIntent_id, []);
    }


    public function sendMail($search = [], $replace = [], $user_id = 0, $emailTo = '', $email = null)
    {

        if (!empty($emailTo) && $user_id > 0) {


            if (isset($email->email_body) && !empty($email->email_body)) {
                $email_body = $this->remove_whitespace_and_newline($email->email_body);
                if ((is_array($search) && count($search) > 0) && (is_array($replace) && count($replace) > 0)) {
                    $email_body = str_replace($search, $replace, $email_body);
                }

                $emailData = [];

                $emailData["body"] = $email_body;
                $emailData["subject"] = $email->subject;
                $emailData["to"] = $emailTo;


                dispatch(new \App\Jobs\GenericMailJOb($emailData))->onQueue('emails');
            }

        }
    }

    public function remove_whitespace_and_newline($html)
    {
        // Remove spaces and newlines between HTML tags
        $html = preg_replace('/>\s+</', '><', $html);
        return $html;
    }

    public function updateInvoice($invoice_id, $data)
    {
        return $this->stripe->invoices->update($invoice_id, $data);
    }

    public function upcomingInvoice($customer_id, $data = [])
    {
        if (isset($data['subscription'])) {
            return $this->stripe->invoices->upcoming(['customer' => $customer_id, 'subscription' => $data['subscription']]);
        }
        return $this->stripe->invoices->upcoming(['customer' => $customer_id]);
    }

    public function retrieveInvoice($invoiceId)
    {
        return $this->stripe->invoices->retrieve($invoiceId);
    }

    public function payInvoice($invoiceId)
    {
        $invoice = $this->retrieveInvoice($invoiceId);
        $paidInvoice = null;
        if ($invoice->status == 'open') {
            $paidInvoice = $invoice->pay();
        }
        if ($invoice->status == 'draft') {
            $finalizedInvoice = $invoice->finalizeInvoice();
            $paidInvoice = $finalizedInvoice->pay();
        }
        return $paidInvoice;
    }
}
