<?php

namespace Modules\Plans\Http\Controllers;


use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Plans\Http\Requests\TalkToSalesAddRequest;
use App\Models\TalkToSales;
use App\Jobs\TalkToSalesJob;
class TalkToSalesController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
        $sales = TalkToSales::paginate($request->limit?? config('settings.record_per_page'));
        return response()->json($sales);
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
    public function store(TalkToSalesAddRequest $request)
    {
        $talkToSales = TalkToSales::create($request->all());
        // $status = Mail::to('adnan.crecentech@gmail.com')->send(new TalkToSalesEmail($talkToSales));
        dispatch(new TalkToSalesJob($talkToSales));
        return response()->json([
            'talkToSales' => $talkToSales
        ]);
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Jsonable
     */
    public function show($id)
    {
        return response()->json([]);
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
        return response()->json([]);
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Jsonable
     */
    public function destroy($id)
    {
        return response()->json([]);
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Jsonable
     */
    public function updateSubscriptionItems(SubscriptionItemsRequest $request, $id)
    {
        return response()->json([]);
    }

    public function testr(){
        (new \App\Services\GracePeriod())->grace_period_start();
    }

}
