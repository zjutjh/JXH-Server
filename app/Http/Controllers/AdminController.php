<?php

namespace App\Http\Controllers;

use App\Services\UserCenterService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function login(Request $request) {
        $username = $request->get('username');
        $passwd = $request->get('passwd');

        $uCenter = new UserCenterService();
        if (!$error = $uCenter->checkJhPassport($username, $passwd)) {
            $error = $uCenter->getError();
            return RJM(null, -1,  $error ? $error : '用户名或密码错误');
        }

        if (!$user = User::where('sid', $username)->first()) {
            return RJM(null, -1, '用户不存在');
        }

        if (!$user->isAdmin) {
            return RJM(null, -1, '用户无权限');
        }

        if (!$token = Auth::login($user)) {
            return RJM(null, -1, '生成token失败');
        }

        return RJM(['user' => $user, 'token' => $token], 1, '登陆成功');
//        return RJM(['user' => 's', 'token' => 'sdf'], 1, '登陆成功');
    }




}
