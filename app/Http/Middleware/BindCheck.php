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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $wuser = app('wechat')->oauth->user();
        $openid = $wuser->getId();
        $wuser = app('wechat')->user->get($openid);
        dd($wuser);

        if ($wuser['subscribe']) {
            return redirect('http://weixin.qq.com/r/TjozK_-EzbKyratI929c');
        }

        if (!$user = User::where('openid', $openid)->first()) {
            return redirect('/oauth');
        }

        if (!$user->sid) {
            return redirect('/oauth');
        }




//        session(['openid' => $openid]);
//        Redis::set('user.' . $user->id, $openid);
        return $next($request);
    }
}
