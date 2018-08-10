<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Psr\Http\Message\RequestInterface;

class OauthController extends Controller
{
    public function oauth(Request $request) {
        $oauth = app('wechat')->oauth->setRequest($request)->redirect();

        return $oauth;
    }

    public function redirect(Request $request) {
        $redirect_url = $request->get('url');
        return redirect($redirect_url);
    }

    public function oauthCancel(Request $request) {
        $url = build_oauth_redirect('http://jxh.jh.zjut.edu.cn/user/cancel/send');
        $oauth = app('wechat')->oauth->setRequest($request)->redirect($url);
        return $oauth;
    }



    public function classmateOauth(Request $request) {
        $url = build_oauth_redirect('http://jxh.jh.zjut.edu.cn/classmate');
        $oauth = app('wechat')->oauth->setRequest($request)->redirect($url);
        return $oauth;
    }


    public function toClassmateDetail() {
        $wuser = app('wechat')->oauth->user();
        $openid = $wuser->getId();
        if (!$user = User::where('openid', $openid)->first()) {
            return redirect('/oauth');
        }
        if (!$user->sid) {
            return redirect('/oauth');
        }
        $stuCode = str_random(20);

        Redis::set($stuCode, $user->sid);
        Redis::expire($stuCode, 60 * 5);
        $param = [
            'stucode' => $stuCode
        ];
        return redirect(env('CLASSMATE') . '?'.http_build_query($param));
    }
    public function stuCodeToSid(Request $request) {
        $stuCode = $request->get('stdcode');

        $sid = Redis::get($stuCode);
        Redis::del($stuCode);
        if ( !isset($sid)) {
            return RJM(null, -1, 'code 过期');
        }
        return RJM(['sid' => $sid], 1, '获取成功');
    }
}
