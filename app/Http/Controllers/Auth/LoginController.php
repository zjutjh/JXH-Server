<?php

namespace App\Http\Controllers\Auth;

use App\Jobs\AsyncUnionId;
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
            $temp = User::where('sid', $request->get('username'));
            return view('jxh.success', ['user' => $temp]);
        }
        $username = $request->get('username');
        $passwd = $request->get('passwd');


        $uCenter = new UserCenterService();

        if (!$error = $uCenter->checkJhPassport($username, $passwd)) {
            $error = $uCenter->getError();
            return RJM(null, -1,  $error ? $error : '用户名或密码错误');
        }

        if (!!$user = User::where('sid', $username)->first()) {
            $uOpenid = $user->openid;
            if (session('openid') === $uOpenid) {
                return RJM(null, 100, '已经绑定');
            }
        }

        if (!$user = User::where('openid', session('openid'))->first()) {
            return RJM(null, -1,  'openid不存在');

        }

        $user->sid = $username;
        $user->save();


        session(['username', $username]);
        Log::info('用户绑定成功', ['username' => $username]);

        return RJM(null, 1, '绑定成功');
    }

    public function wechat(Request $request) {

          $wuser = app('wechat')->oauth->user();

          $openid = $wuser->getId();
          Log::info('微信授权成功', ['openid' => $openid]);
          if (!$user = User::where('openid', $openid)->first()) {
              $user = new User();
              $user->openid = $wuser->getId();
              $user->name = $wuser->getName();
              $user->nickname = $wuser->getNickname();
              $user->avatar = $wuser->getAvatar();
          }

//          $user->sid = session('username');
          $user->save();
          if (!$user->unionID) {
              AsyncUnionId::dispatch($openid);
          }

          session(['openid' => $openid]);
          if (!$user->sid) {
              return view('jxh.bind');
          }

          return view('jxh.mine', ['user' => $user]);
    }


    public function agreeSend(Request $request) {
        $username = $request->get('username');
        if (!$user = User::where('sid', $username)->first()) {
            return  RJM(null, -1, '有一点错误');
        }

        $user->allow_send = true;
        $user->save();

        Log::info('用户同意发送学校通知', ['username' => $username]);
        return RJM(null, 1, '确认成功');
    }


    public function cancel() {
        // todo 取消通知
        $openid = session('openid');
        if (!$user = User::where('openid', $openid)->first()) {
            return  RJM(null, -1, '有一点错误');
        }
        $user->allow_send = false;
        $user->save();
        Log::info('用户取消发送学校通知', ['username' => $user->sid]);
        return RJM(null, 1, '取消成功');
    }

    public function changeBind() {
        return view('jxh.bind');
    }
}
