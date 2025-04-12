<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;
use Modules\Plans\Http\Requests\CustomerAddRequest;

class CustomerController extends Controller
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
            'customers' => $this->stripeService->customers(),
        ]);
    }

    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function search(Request $request)
    {
        return response()->json([
            'customers' => $this->stripeService->searchCustomers($request->all()),
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
    public function store(CustomerAddRequest $request)
    {
        return response()->json([
            'customer' => $this->stripeService->upsertCustomer($request->email, $request->all()),
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
            'customer' => $this->stripeService->retrieveCustomer($id),
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
     * Remove the specified resource from storage.
     * @param int $id
     * @return Jsonable
     */
    public function destroy($id)
    {
        $customer = $this->stripeService->deleteCustomer($id);
        return response()->json([
            'customer' => $customer,
        ]);
    }
}
