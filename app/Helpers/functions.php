<?php
/**
 * Created by PhpStorm.
 * User: zhutianyu
 * Date: 2018/6/14
 * Time: 11:22
 */

/**
 * 响应json数据
 * @param  mixed    $data
 * @param  integer  $err_code
 * @param  string   $err_msg
 * @param  string   $redirect_url
 * @return \Symfony\Component\HttpFoundation\Response
 */
function RJM($data, $code, $msg = '', $redirect_url = null)
{
    return response([
        'code' => $code,
        'error' => $msg,
        'data' => $data,
        'redirect' => $redirect_url,
    ]);
}



/**
 * wechat 辅助函数
 */


function buildOauthRedirect($url, $scope = 'snsapi_userinfo') {
    $redirect_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='
        .env('WECHAT_APP_ID')
        .'&redirect_uri='
        .urlencode(config('api.jh.oauth'))
        .urlencode($url)
        .'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
    return $redirect_url;
}

/**
 * end
 */