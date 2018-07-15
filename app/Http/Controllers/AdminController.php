<?php

namespace App\Http\Controllers;

use App\Jobs\SendAllUserMessage;
use App\Message;
use App\Services\UserCenterService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class AdminController extends Controller
{
    public function login(Request $request) {
        $username = $request->get('username');
        $passwd = $request->get('password');

        $uCenter = new UserCenterService();
        if (!$error = $uCenter->checkJhPassport($username, $passwd)) {
            $error = $uCenter->getError();
            return RJM(null, -1,  $error ? $error : '用户名或密码错误');
        }

        if (!$user = User::where('sid', $username)->first()) {
            return RJM(null, -1, '用户不存在');
        }

        if (!$user->isAdmin()) {
            return RJM(null, -1, '用户无权限');
        }

        if (!$token = Auth::login($user)) {
            return RJM(null, -1, '生成token失败');
        }

        return RJM(['user' => $user, 'token' => $token], 1, '登陆成功');
//        return RJM(['user' => 's', 'token' => 'sdf'], 1, '登陆成功');
    }


    public function agree($hashid) {
        $messageId = Redis::get($hashid);
        $message = Message::where('id', $messageId)->first();


        SendAllUserMessage::dispatch($message, [
            'template_id' => config('templatemsg.message.template_id')
        ]);
        $message->is_send = true;
        $message->save();
        Redis::del($hashid);
        return RJM(null, 1, '已经发送');

    }

    public function cancel($hashid) {
        Redis::del($hashid);
        return RJM(null, 1, '取消成功');

    }

    public function show($hashid) {
        $messageId = Redis::get($hashid);
        if ( !isset($messageId)) {
            return view('jxh.success', ['content' => '已经过期']);
        }
        return view('admin.sure', ['hashid' => $hashid]);
    }




}
