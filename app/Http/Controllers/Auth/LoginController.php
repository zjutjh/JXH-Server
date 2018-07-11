<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\UserCenterService;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{

    public function login(Request $request) {
        if ($request->session()->has('openid')) {
            // todo 跳转已经绑定
            return view('');
        }
        $username = $request->get('username');
        $passwd = $request->get('passwd');

        $uCenter = new UserCenterService();

        if (!$error = $uCenter->checkJhPassport($username, $passwd)) {
            $error = $uCenter->getError();
            return RJM(null, -1, ['error' => $error ? $error : '用户名或密码错误']);
        }

        session(['username', $username]);

        return redirect('/oauth');
    }

    public function wechat(Request $request) {
          $user = app('wechat')->oauth->user();
          $new_user = new User();
          $new_user->openid = $user->getId();
          $new_user->name = $user->getName();
          $new_user->sid = session('username');
          $new_user->nickname = $user->getNickname();
          $new_user->avatar = $user->getAvatar();
          $new_user->save();
          session(['openid' => $user->getId()]);
          return view('');
    }


    public function agreeSend() {


    }
}
