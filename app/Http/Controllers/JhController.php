<?php

namespace App\Http\Controllers;

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
        $config = [
            'template_id' => config('templatemsg.message.template_id'),
            // todo url
            'url' => url('message/show', 12),
            'data' => [
                'first' => ['精弘网络开发部第二轮面试通知', '#05328E'],
                'keyword1' => '浙江工业大学',
                'keyword2' => '精弘网络开发部',
                'keyword3' => date('Y-m-d H:i:s', time()),
                'keyword4' => ['精弘网络第二轮面试通知', '#4D0015'],
                'remark' => '点击查看详情'
            ]
        ];

        foreach ($stuArr as $k) {
            if (!$user = User::where('sid', $k[0])->first()) {
                $user->notify(new TemplateMessage($config));
            }
        }

        return RJM(null, 1, '已经全部发送');

    }
}
