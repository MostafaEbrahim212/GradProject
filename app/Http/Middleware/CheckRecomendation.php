<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRecomendation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('api')->check()) {
            $user = Auth::guard('api')->user();
            if ($user->has_recommendation === 1) {
                return $next($request);
            }
            if (in_array($request->route()->getName(), ['user.recommendation', 'login', 'register', 'logout', 'user.checkRecommendation'])) {
                return $next($request);
            } else {
                return response()->json(['message' => 'You are not allowed to access this route until recommend'], 401);
            }
        }
        return $next($request);
    }
}
