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

function trim_words($content, $num_words, $more = '...') {
    $strip_tags_content = preg_replace( '@<(script|style)[^>]*?>.*?</\\1>@si', '', $content);
    $strip_tags_content = strip_tags($strip_tags_content);
    $strip_tags_content = trim($strip_tags_content);
    $strip_tags_content = str_replace('&nbsp;', '', $strip_tags_content);
    $resutlt = mb_substr($strip_tags_content, 0, $num_words, 'utf-8');
    return $resutlt;
}

/** 创建item唯一id
 * @param $itemId
 * @return string
 */
function create_messageUid($itemId) {
    $itemUid = str_random(16);

    \Illuminate\Support\Facades\Redis::set($itemUid, $itemId);
    \Illuminate\Support\Facades\Redis::expire($itemUid, 60 * 30);

    return $itemUid;
}


function create_to_super_admin_config($message_Uid) {
    return  [
        'template_id' => config('templatemsg.message.template_id'),
        'url' => url('message/admin/show', [$message_Uid]),
        'data' => [
            'first' => '请你确认发送',
            'keyword1' => '浙江工业大学',
            'keyword2' => '精小弘后台',
            'keyword3' => date('Y-m-d H:i:s', time()),
            'keyword4' => '',
            'remark' => '点击查看详情'
        ]
    ];
}


/**
 * 处理关键字匹配
 */
function is_match($message, \App\Reply $reply) {
    switch ($reply->type) {
        case 1:
            return $message == $reply->reply_content;
            break;
        case 2:
            return !!strstr($message, $reply->reply_content);
            break;
        case 3:
            return !!preg_match('/(' . $reply->reply_content . ')/is', $message);
            break;
        default:
            return false;
    }
}


function to_unicode($string)
{
    $str = mb_convert_encoding($string, 'UCS-2', 'UTF-8');
    $arrstr = str_split($str, 2);
    $unistr = '';
    foreach ($arrstr as $n) {
        $dec = hexdec(bin2hex($n));
        $unistr .= '&#' . $dec . ';';
    }
    return $unistr;
}

/**
 * end
 */