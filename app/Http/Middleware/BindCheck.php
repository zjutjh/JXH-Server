<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class BindCheck
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
        $wuser = app('wechat')->oauth->user();
        $openid = $wuser->getId();
        if (!$user = User::where('openid', $openid)->first()) {
            return redirect('/oauth');
        }
        if (!$user->sid) {
            return redirect('/oauth');
        }
        return $next($request);
    }
}
