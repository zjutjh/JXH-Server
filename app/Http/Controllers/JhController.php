<?php

namespace App\Http\Controllers;

use App\Message;
use App\Notifications\TemplateMessage;
use App\User;
use Illuminate\Http\Request;

class JhController extends Controller
{
    public function sendMsResult(Request $request) {
        $stuCsv = $request->file('csv');
//        dd($stuCsv);
        $file = fopen($stuCsv->getRealPath(), 'r');
        $stuArr = [];
        while ($data = fgetcsv($file)) {
            $stuArr [] = $data;
        }
        $message = Message::where('id', 12)->first();
        $config = [
            'template_id' => config('templatemsg.message.template_id'),
            // todo url
            'url' => url('message/show', [12]),
            'data' => [
                'first' => [$message->title, '#05328E'],
                'keyword1' => '浙江工业大学',
                'keyword2' => $message->informer,
                'keyword3' => date('Y-m-d H:i:s', time()),
                'keyword4' => [$message->notify_content, '#4D0015'],
                'remark' => '点击查看详情'
            ]
        ];

        foreach ($stuArr as $k) {
            if (!$user = User::where('sid', $k[0])->first()) {
                continue;
            }
            $user->notify(new TemplateMessage($config));
        }

        return RJM(null, 1, '已经全部发送');

    }
}
