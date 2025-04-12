<?php

namespace Modules\Plans\Http\Controllers;

use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\StripeService;

class PlansController extends Controller
{
    private $stripe = null;
    public function __construct()
    {
        $this->stripe = new StripeService();
    }
    /**
     * Display a listing of the resource.
     * @return Renderable
     */
    public function index()
    {
        return view('plans::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Renderable
     */
    public function create()
    {
        return view('plans::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function show($id)
    {
        return view('plans::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Renderable
     */
    public function edit($id)
    {
        return view('plans::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }

    public function setupintent(Request $request) {

        try {
            if(!empty($request->id)) {
                $setupIntentsArray = $this->stripe->setupIntents([
                    'company_id' => $request->company_id,
                ], $request->id);
                $setupIntentsArray = $setupIntentsArray->toArray();
            } else {
                $setupIntents = $this->stripe->setupIntents([
                    'company_id' => $request->company_id,
                    'customer_id' => $request->customer_id,
                ]);
                $setupIntentsArray = $setupIntents;//->toArray();
            }
            return response()->json([
                'setupIntents' => $setupIntentsArray
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function setupintentconfirm(Request $request) {
        try {
            ['setupPaymentIntent' => $setupIntentsArray, 'customerNewUpdate' => $customerNewUpdate] = $this->stripe->setupIntentsConfirm($request->all());

            return response()->json([
                'setupPaymentIntent' => $setupIntentsArray,
                'customerNewUpdate' => $customerNewUpdate
                ]
            );
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
