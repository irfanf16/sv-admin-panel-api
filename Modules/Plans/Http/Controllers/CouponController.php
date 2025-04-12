<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
use Modules\Plans\Http\Requests\CouponsAddRequest;
use Modules\Plans\Http\Requests\CouponsUpdateRequest;

class CouponController extends Controller
{
    private $stripeService = null;
    public function __construct() {
        $this->stripeService = new StripeService();
    }
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index()
    {
        return response()->json([
            'coupons' => $this->stripeService->coupons(),
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
    public function store(CouponsAddRequest $request)
    {
        return response()->json([
            'coupon' => $this->stripeService->createCoupon($request),
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function show($id)
    {
        return response()->json([
            'coupon' => $this->stripeService->coupon($id),
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
    public function update(CouponsUpdateRequest $request, $id)
    {
        return response()->json([
            'coupon' => $this->stripeService->updateCoupon($request, $id),
        ]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Jsonable
     */
    public function destroy($id)
    {
        return response()->json([
            'coupon' => $this->stripeService->deleteCoupon($id),
        ]);
    }
}
