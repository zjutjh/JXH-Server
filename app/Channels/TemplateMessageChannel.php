<?php
/**
 * Created by PhpStorm.
 * User: 70473
 * Date: 2018/6/16
 * Time: 20:57
 */

namespace App\Channels;




use Illuminate\Notifications\Notification;

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
    }

}