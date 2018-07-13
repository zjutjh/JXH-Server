<?php
/**
 * Created by PhpStorm.
 * User: 70473
 * Date: 2018/6/16
 * Time: 20:57
 */

namespace App\Channels;




use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Log;

class TemplateMessageChannel
{

    public $templateSender;

    public function __construct()
    {
        $this->templateSender = app('wechat')->template_message;
    }

    public function send($notifiable, Notification  $notification)
    {
        $templateMsg = $notification->toTemplate($notifiable);
        $this->templateSender->send($templateMsg);
        Log::info("发送模板消息", ['sid' => $notifiable->sid]);
    }

}