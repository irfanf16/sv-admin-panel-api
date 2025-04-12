<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\AddonsHistory;
use App\Http\Requests\AddonsHistoryRequest;
class AddonsHistoryController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function upsert(AddonsHistoryRequest $request)
    {
        $result['addHistoryData'] = AddonsHistory::insertOrUpdateCustomize($request->get("addons_history"));
        return response()->json($result, 200);
    }
}
