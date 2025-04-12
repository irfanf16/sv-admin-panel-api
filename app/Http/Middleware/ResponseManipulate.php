<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Services\EncryptionDecryption;
class ResponseManipulate
{
    /**
     * Handle an OutGoing response.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        if ($request->header('token') == getenv('INTERNAL_SYSTEM_COMMUNICATION') || str_contains($request->header('User-Agent'), 'stripe')) {
            return $response;
        }
        if (str_contains($request->header('User-Agent'), 'Postman') ) {
            return $response;
        }
        $encryptionDecryption = new  EncryptionDecryption;
        $json_response = $response->getContent();
        $res = $encryptionDecryption->encrypt($json_response);
        $response->setContent(json_encode(['app_data' => $res]));
        return $response;
    }
}
