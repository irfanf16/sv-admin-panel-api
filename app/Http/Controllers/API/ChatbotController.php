<?php
namespace App\Http\Controllers\API;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Chatbot;
class ChatbotController extends BaseController
{
    /**
     * Register api
     *
     * @return \Illuminate\Http\Response
     */
    public function save_guest_client(Request $request)
    {
        $client = Chatbot::create($request->all());
        return response()->json($client, 200);
    }
    public function save_guest_client_rating(Request $request)
    {
        $client = Chatbot::updateOrCreate(['id' => $request->id], $request->all());
        return response()->json($client, 200);
    }
}
