<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OauthController extends Controller
{
    public function oauth(Request $request) {
        $oauth = app('wechat')->oauth()->setRequest($request)
            ->redirect();
        return $oauth;
    }

    public function redirect(Request $request) {
        $redirect_url = $request->get('url');
        return redirect($redirect_url);
    }
}
