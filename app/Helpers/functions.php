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
        'msg' => $msg,
        'data' => $data,
        'redirect' => $redirect_url,
    ]);
}



/**
 * wechat 辅助函数
 */


function build_oauth_redirect($url, $scope = 'snsapi_userinfo') {
    $redirect_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid='
        .env('WECHAT_APP_ID')
        .'&redirect_uri='
        .urlencode(config('api.jh.oauth'))
        .urlencode($url)
        .'&response_type=code&scope='.$scope.'&state=STATE#wechat_redirect';
    return $redirect_url;
}


function trim_words($content, $num_words, $more = '...') {
    $strip_tags_content = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $content);
    $strip_tags_content = strip_tags($strip_tags_content);
    $strip_tags_content = trim($strip_tags_content);
    $strip_tags_content = str_replace('&nbsp;', '', $strip_tags_content);
    $resutlt = mb_substr($strip_tags_content, 0, $num_words, 'utf-8');
    return $resutlt;
}

/**
 * end
 */