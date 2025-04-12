<?php

namespace Modules\Affiliate\App\Http\Controllers;

use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Services\TrackdeskService;
use App\Jobs\TrackdeskWebhookJob;
class TrackdeskWebhookController extends Controller
{
    private $trackdeskWebhookService = null;
    public function __construct() {
        $this->trackdeskWebhookService = new TrackdeskService();
    }
    /**
     * Display a listing of the resource.
     * @return Jsonable
     */
    public function index(Request $request)
    {
 
        try {
            $data = $request->all();
            $event = $this->trackdeskWebhookService->isValidRequest($data);
            \Log::channel('trackdesk_webhook')->info('trackdesk Webhook Data::'. json_encode($data));
            if($event == false) {
                return response()->json([
                    'message' => 'Invalid Request',
                ], 500);
            }
            if(isset($data["affiliateCreated"]) && is_array($data["affiliateCreated"])) {
                // $this->trackdeskWebhookService->saveAffiliate($data);
                dispatch(new TrackdeskWebhookJob($data));
            }

        } catch (\Exception $ex) {
            \Log::channel('trackdesk_webhook')->error('Hook data is invalid. Error Message:: '. $ex->getMessage());
            return response()->json([
                'message' => 'Invalid Request',
            ], 500);
        }

        return response()->json($data);
    }
    public function alive() {
        echo 'I am alive';
    }
}
