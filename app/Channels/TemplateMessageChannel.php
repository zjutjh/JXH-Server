<?php
/**
 * Created by PhpStorm.
 * User: 70473
 * Date: 2018/6/16
 * Time: 20:57
 */

namespace App\Channels;


use Illuminate\Notifications\Notifiable;

class TemplateMessageChannel
{

    public $templateSender;

    public function __construct()
    {
        $this->templateSender = app('wechat')->template_message;
    }

    public function send($notifiable, Notifiable $notification)
    {
        $templateMsg = $notifiable->toTemplate();
        $this->templateSender->send($templateMsg);
    }

}