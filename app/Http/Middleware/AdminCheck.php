<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Contracts\Providers\Auth;


class AdminCheck
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if (!$user->isAdmin()) {
            return RJM(null, -1, '用户无权限');
        }
        return $next($request);
    }
}
