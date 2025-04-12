<?php

namespace Modules\Affiliate\App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Affiliates;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\TrackdeskService;
use App\Services\FirstpromoterService;
use App\Http\Requests\AffiliateRegistrationFormRequest;

class AffiliateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('affiliate::index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('affiliate::create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(AffiliateRegistrationFormRequest $request): mixed 
    {
        $trackdesk = new TrackdeskService();
        return $trackdesk->createAffiliate($request->all());
    }

    /**
     * Show the specified resource.
     */
    public function show($email)
    {
        $firstPromoterService = new FirstpromoterService();
        $affiliate = $firstPromoterService->showPromoter($email);
        if(isset($affiliate['error'])) {
            return response()->json($affiliate, 404);
        }
        return response()->json($affiliate, 200);
    }

    /**
     * Show the specified resource.
     */
    public function showOld($email)
    {
        $affiliate = Affiliates::where('email', $email)->first();
        if(!empty($affiliate)) {
            return response()->json($affiliate, 200);
        } else {
            return response()->json([], 404);
        }
       
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        return view('affiliate::edit');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        //
    }
}
