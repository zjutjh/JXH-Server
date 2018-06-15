<?php

namespace App\Http\Controllers\Wechat;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ServerController extends Controller
{
    public $wechat;

    public function __construct() {
        $this->wechat = app('wechat');
    }


    public function server() {
        $this->wechat->server->push(function ($message)  {
            $type = $message->MsgType;
            try {
                $res = $this->$type($message);
                return $res;
            } catch (BadMethodCallException $e) {
                return '问题来了';
            }
        });

        $res = $this->wechat->server->serve();
        return $res;
    }



}
