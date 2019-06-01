<?php

namespace App\Http\Controllers;

use App\Jobs\GetByz;
use App\Services\FaceMergeServices;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Intervention\Image\ImageManager;
use Intervention\Image\ImageManagerStatic;

class GdByzController extends Controller
{

    public function __construct()
    {

    }


    public function oauth(Request $request) {
        $oauth = app('wechat')->oauth->setRequest($request)->redirect(url('jxh/byz'));
        return $oauth;
    }



    public function index() {
        $wuser = app('wechat')->oauth->user();
        $openid = $wuser->getId();
        $wuser = app('wechat')->user->get($openid);

        if (!$wuser->subscribe) {
            return redirect('https://mp.weixin.qq.com/mp/profile_ext?action=home&__biz=MzI0MTUzNzQzMg==&scene=126&bizpsid=0#wechat_redirect');
        }

        if (!$user = User::where('openid', $openid)->first()) {
            return redirect('/oauth');
        }

        if (!$user->sid) {
            return redirect('/oauth');
        }


        Redis::set('user.'.$user->id, $openid);

        return view('gdbyz.upload', ['user' => encrypt($user->id)]);
    }


    public function getZjz(Request $request) {
        return view('gdbyz.upload');
    }


    public function submit(Request $request) {
        $data = $request->all();
        $openid = Redis::get('user.' . decrypt($data['user']));
        GetByz::dispatch($openid, $data);
        return RJM(null, 1, '提交成功');
    }


    public function await() {
        return view('gdbyz.prompt');
    }

    public function upload(Request $request) {
        $file = $request->file('file');
        $path = $file->store('public/images');
        $url = substr($path, 7);
        $url = url('storage/' . $url);
        return RJM(['url'=> $url], 1, '上传成功');
    }


    public function show($hashId) {
        $img = Redis::get('img.' . $hashId);

        return view('gdbyz.byz', ['img' => $img]);
    }



}
