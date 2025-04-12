<?php

namespace Modules\Plans\Http\Controllers;


use App\Models\UserSubscriptionDetail;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Plans\App\Http\Requests\UserSubscriptionDetailPostRequest;

class UserSubscriptionDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
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
    public function store(UserSubscriptionDetailPostRequest $request): JsonResponse
    {
        // Create a new UserInfo instance
        $userInfo = UserSubscriptionDetail::create([
            'user_id' => $request->input('user_id'),
            'ip_address' => $request->input('ip_address'),
            'os_info' => $request->input('os_info'),
            'location' => $request->input('location'),
            'terms_acceptance_time' => date('Y-m-d H:i:s'),
            'terms_details' => $request->input('terms_details'),
        ]);

        // Return a response or redirect
        return response()->json([
            'message' => 'User info stored successfully',
            'data' => $userInfo
        ], 200);
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



}
