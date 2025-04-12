<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\EncryptionDecryption;
class RequestManipulate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
//        if ($request->header('token') == getenv('INTERNAL_SYSTEM_COMMUNICATION') || str_contains($request->header('User-Agent'), 'stripe')) {
        if ($request->header('token') == getenv('INTERNAL_SYSTEM_COMMUNICATION')
            || str_contains($request->header('User-Agent'), 'stripe')
            || ($request->has("tenantId") && $request->tenantId == getenv("TRACKDESK_TENANTID"))
        ) {
            return $next($request);
        }
        if (str_contains($request->header('User-Agent'), 'Postman') ) {
            return $next($request);
        }
        $incomingMethod = strtolower($request->method());
        $encryptionDecryption = new  EncryptionDecryption;
        if(in_array($incomingMethod, ['post', 'put'])) {
            try {

                $incomingData = $encryptionDecryption->decrypt($request->app_data);

                $incomingData = json_decode($incomingData,1);

                $request->merge($incomingData);

                $request->offsetUnset('app_data');


            } catch (\Throwable $th) {
                return response(['error' => $th->getMessage()],403);
            }
        }
        return $next($request);
    }
}
