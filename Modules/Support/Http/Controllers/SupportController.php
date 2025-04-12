<?php

namespace Modules\Support\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Modules\Support\Http\Requests\ValidateCodeRequest;
use App\Models\Code;
use App\Services\EncryptionDecryption;

class SupportController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function validateCode(ValidateCodeRequest $request) {
       
        $result = Code::validateCode($request->code, $request->fields)->first();
        
        if($result) {
            $encryptionDecryption = new  EncryptionDecryption;
            $result->agent_id = Auth()->user()->id;
            $json = json_encode($result);
            $res = $encryptionDecryption->encrypt($json, 'ENCRYPTION_KEY_FOR_AGENT_LOGIN');
            $result->login_url = getenv("APP_STAFFVIZ_URL") . '/support/agent/login/' . $res;
            return $result;
        }
        
        return [];
         
    }
}
