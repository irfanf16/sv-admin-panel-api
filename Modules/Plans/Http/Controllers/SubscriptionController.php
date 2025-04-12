<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
use Modules\Plans\Http\Requests\SubscriptionAddRequest;
use Modules\Plans\Http\Requests\SubscriptionUpdateRequest;
use App\Models\Products;
use Modules\Plans\Http\Requests\SubscriptionItemsRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
class SubscriptionController extends Controller
{
    private $stripeService = null;
    public function __construct() {
        $this->stripeService = new StripeService();
    }
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
        $search['query'] = "active:'true'";
        if(!empty($request->type)) {
            $search['query'] .= " AND metadata['type']:'".$request->type."'";
        }
        return response()->json([
            'products' => $this->stripeService->searchProducts($search),
        ]);
    }
    /**
     * Show the form for creating a new resource.
     * @return Jsonable
     */
    public function create()
    {
        return response()->json([]);
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Jsonable
     */
    public function store(SubscriptionAddRequest $request)
    {
        $subscription = $this->stripeService->createSubscription($request->all());
        return response()->json([
            'subscription' => $subscription,
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function show($id)
    {
        $subscription = $this->stripeService->getSubscription($id);
        return response()->json([
            'subscription' => $subscription,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function edit($id)
    {
        return response()->json([]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Jsonable
     */
    public function update(SubscriptionUpdateRequest $request, $id)
    {
        // return response()->json([
        //     'subscription' => $this->stripeService->updateSubscription($id, $request->all())
        // ]);
        return response()->json($this->stripeService->updateSubscription($id, $request->all()));

    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Jsonable
     */
    public function removeSubscriptionItems(SubscriptionUpdateRequest $request, $id)
    {
        // return response()->json([
        //     'subscription' => $this->stripeService->updateSubscription($id, $request->all())
        // ]);
        return response()->json($this->stripeService->removeSubscriptionItems($id, $request->all()));

    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Jsonable
     */
    public function destroy($id)
    {
        return response()->json([
            'subscription' => $this->stripeService->cancelSubscription($id),
        ]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Jsonable
     */
    public function updateSubscriptionItems(SubscriptionItemsRequest $request, $id)
    {
        return response()->json([
            'subscription' => $this->stripeService->createSubscription($request->all(),$id)
        ]);
    }

    public function updateSubscriptionQuantity(Request $request, $id) {
        try {
            $result = $this->stripeService->updateSubscriptionQuantity($id,$request->all());
            return response()->json($result);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 402);
        }
    }

    public function start_trial(Request $request) {
        // $company_id = 216;
        // $user_id = 479;
        // $company = (new \App\Models\Company())::where(['id' => $company_id,'super_admin_id' => $user_id])->first();
        // $user = (new \App\Models\UserTableMaster())::where(['id' => $user_id])->first();
        // $date = new \Carbon\Carbon($company->plan_expiry);
        // $diff_in_days = $date->diffInDays();
        // dispatch(new \App\Jobs\StartTrialJob($company, $user));
        // dd($user, $company);
        // $company_id = 220;
        // $company = (new \App\Models\Company())::where(['id' => $company_id])->first();
        // $database = \DB::select("SHOW DATABASES LIKE '".$company->company_initial."asdf'");
        // dd($database, count($database));
        $company = (new \App\Models\Company())::where(['id' => '122'])->first();;
        (new \App\Services\GracePeriod)->blockUsers($company);
        // $companies = (new \App\Services\GracePeriod)->index();
        // dd($companies);
    }
    public function grace_period_run(Request $request) {
        (new \App\Services\GracePeriod)->index();
        return response()->json([
            'message' => 'Grace period executed',
        ], 200);
    }

    public function coupon($id)
    {
        return response()->json([
            'coupon' => $this->stripeService->coupon($id)
        ]);
    }
    public function addonsHistoryRemove(Request $request)
    {
        try {
            $stripe_product_ids = $request->stripe_product_ids;
            $company_id = $request->company_id;

            DB::table('addons_histories')
                ->where('company_id', $company_id)
                ->whereIn('stripe_product_id', $stripe_product_ids)
                ->delete();

            return response()->json([
                'message' =>'Addon history removed successfully',
            ]);
        } catch (\Exception $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

    }
}
