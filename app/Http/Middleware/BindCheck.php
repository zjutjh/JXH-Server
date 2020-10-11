<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Redis;

class BindCheck
{
    /**
     * Handle an incoming request.
     *
     * @param Illuminate\Http\Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $wuser = app('wechat')->oauth->user();
        $openid = $wuser->getId();
        $user = User::where('openid', $openid)->first();
        if (!$user)
            return redirect('/oauth');

        if (!$user->sid)
            return redirect('/oauth');



        session(['openid' => $openid]);

        return $next($request);
    }
}
