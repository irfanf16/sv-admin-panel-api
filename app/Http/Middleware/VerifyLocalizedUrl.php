<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Facades\TypiCMS;

class VerifyLocalizedUrl
{
    public function handle(Request $request, Closure $next)
    {
        if (
            config('typicms.main_locale_in_url') &&
            $request->segment(1) !== null &&
            !in_array($request->segment(1), TypiCMS::enabledLocales())
        ) {
            abort(404);
        }

        return $next($request);
    }
}
