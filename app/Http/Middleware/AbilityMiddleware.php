<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Laravel\Sanctum\Exceptions\MissingAbilityException;

class AbilityMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws AuthenticationException
     */

    public function handle(Request $request, Closure $next, ...$abilities)
    {
        $permittedAbilities = $request->user()->currentAccessToken()->abilities;

        if (is_array($permittedAbilities) && count($permittedAbilities) > 0) {


            $checkAllConditionsNotFalse = false;

            if (!$request->user() || !$request->user()->currentAccessToken()) {
                throw new AuthenticationException;
            }
            foreach ($abilities as $ability) {
                $ability = explode(':', $ability)[1];//str_replace('abilities:', '', $ability);
                if (in_array($ability, $permittedAbilities)) {
                    $checkAllConditionsNotFalse = true;
                }
            }

            dd($checkAllConditionsNotFalse,$abilities,$permittedAbilities,$request->all());
            if (!$checkAllConditionsNotFalse) {
                throw new MissingAbilityException('random');
            }else{
                return $next($request);
            }
        } else {
            throw new MissingAbilityException('random');
        }

    }



//    public function handle(Request $request, Closure $next, ...$abilities)
//    {
//        $permittedAbilities = $request->user()->currentAccessToken()->abilities;
//
//        // Check if the user or the token is missing
//        if (!$request->user() || !$request->user()->currentAccessToken()) {
//            throw new AuthenticationException('User or token not found.');
//        }
//
//        // Check if permittedAbilities is a valid array and has permissions
//        if ($request->has('controllerId') && is_array($permittedAbilities) && count($permittedAbilities) > 0) {
//
//                $ability = $request->controllerId;
//
//                // Log for debugging which ability is being checked
//                \Log::info("Checking required ability: {$ability}");
//
//                // If any ability is not found in permitted abilities, deny access
//                if (!in_array($ability, $permittedAbilities)) {
//                    throw new MissingAbilityException("Permission '{$ability}' is required but not allowed.");
//                }
//
//            // If all required abilities are permitted, proceed with the request
//            return $next($request);
//
//        } else {
//            // No abilities found or empty, throw an exception
//            throw new MissingAbilityException('No abilities found for the current token.');
//        }
//    }



}
