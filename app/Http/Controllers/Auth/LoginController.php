<?php

namespace App\Http\Controllers\Auth;

use App\Notifications\TemplateMessage;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserCenterService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{

    public function login(Request $request) {
        if ($request->session()->has('username')) {
            // todo 跳转已经绑定
            return view('jxh.success');
        }
        $username = $request->get('username');
        $passwd = $request->get('passwd');


        $uCenter = new UserCenterService();

        if (!$error = $uCenter->checkJhPassport($username, $passwd)) {
            $error = $uCenter->getError();
            return RJM(null, -1,  $error ? $error : '用户名或密码错误');
        }

        if (!!$user = User::where('sid', $username)->first()) {
            return view('jxh.success');
        }

        if (!$user = User::where('openid', session('openid')->first())) {
            return RJM(null, -1,  'openid不存在');

        }

        $user->sid = $username;
        $user->save();


        session(['username', $username]);
        Log::info('用户绑定成功', ['username' => $username]);

        return RJM(null, 1, '绑定成功');
    }

    public function wechat(Request $request) {
          $user = app('wechat')->oauth->user();
          $openid = $user->getId();
          if (!$user = User::where('openid', $openid)->first()) {
              $user = new User();
              $user->openid = $user->getId();
              $user->name = $user->getName();
              $user->nickname = $user->getNickname();
              $user->avatar = $user->getAvatar();
          }

//          $user->sid = session('username');
          $user->save();
          session(['openid' => $openid]);



          Log::info('微信授权成功', ['openid' => $user->openid]);

          // todo redirect agree
          return view('jxh.bind');
    }


    public function agreeSend() {
        if (!$username = session('username')) {
            if (!$user = User::where('sid', $username)->first()) {
                return  RJM(null, -1, '有一点错误');
            }
        }

        $user->allow_send = true;
        $user->save();


        Log::info('用户同意发送学校通知', ['username' => session('username')]);
        return RJM(null, 1, '确认成功');
    }
}
