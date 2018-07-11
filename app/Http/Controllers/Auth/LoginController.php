<?php

namespace App\Http\Controllers\Auth;

use App\Notifications\TemplateMessage;
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
          $openid = $user->getId();
          if (!$user = User::where('openid', $openid)->first()) {
              $user = new User();
              $user->openid = $user->getId();
              $user->name = $user->getName();
              $user->nickname = $user->getNickname();
              $user->avatar = $user->getAvatar();
          }

          $user->sid = session('username');
          $user->save();
          session(['openid' => $openid]);

          // todo redirect agree
          return view('');
    }


    public function agreeSend() {
        $user = User::where("sid", "201607420143")->first();
        $config = [
            'template_id' => 'ZR8X_OD4YRTmFpgcjLJXpgq51riQvIJSIC42FVXLNf8',
            // todo url
            'url' => '',
            'data' => [
                'first' => 'test',
                'keyword1' => '浙江工业大学',
                'keyword2' => 'test',
                'keyword3' => '2018',
                'keyword4' => 'testtesttesttestestestestestestestestestest',
                'remark' => '点击查看详情'
            ]
        ];
        $user->notify(new TemplateMessage($config));

    }
}
