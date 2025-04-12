<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocaleFromUser
{
    /**
     * Handle an incoming request.
     *
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        
        $userLocale = auth()->user()->locale ?? 'en';
        
        if (in_array($userLocale, locales())) {
            app()->setLocale($userLocale);
        }

        return $next($request);
    }
}
