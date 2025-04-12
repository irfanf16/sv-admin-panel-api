<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
class PaymentMethodController extends Controller
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
        return response()->json([]);
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
    public function store(Request $request)
    {
        return response()->json([]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function getPaymentMethodsByCustomerId($customer_id)
    {
        return response()->json([
            'payment_methods' => $this->stripeService->getPaymentMethodsByCustomerId($customer_id),
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function getPaymentMethodByCustomerIdPmID($payment_method_id)
    {
        return response()->json([
            'payment_method' => $this->stripeService->getPaymentMethodByCustomerIdPmID($payment_method_id),
        ]);
    }

     /**
     * detach the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function paymentMethodDetach($payment_method_id)
    {
        return response()->json([
            'payment_method' => $this->stripeService->paymentMethodDetach($payment_method_id),
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
    public function update(Request $request, $id)
    {

        return response()->json([]);
    }
}
