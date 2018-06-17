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


