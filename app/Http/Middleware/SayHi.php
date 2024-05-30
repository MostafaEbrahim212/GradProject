<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SayHi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if ($user->has_recommendation === 1) {
            return $next($request);
        }
        if ($request->route()->getName() === 'sayhi') {
            return $next($request);
        } else {
            return res_data('You are not allowed to access this route until recomend', 'Unauthorized', 401);
        }
        // if (in_array($request->route()->getName(), ['sayhi', 'login', 'register'])) {
        //     return $next($request);
        // } else {
        //     if ($user->has_recommendation) {
        //         return $next($request);
        //     } else {
        //         return res_data('You are not allowed to access this route until recomend', 'Unauthorized', 401);
        //     }
        // }
    }
}
