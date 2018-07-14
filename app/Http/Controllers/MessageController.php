<?php

namespace App\Http\Controllers;

use App\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    /**
     * 创建消息推送
     */
    public function create(Request $request) {
        $title = $request->get('title');
        $informer = $request->get('informer');
        $content=  $request->get('content');

        $message = new Message();
        $message->title = $title;
        $message->informer = $informer;
        $message->content = $content;
        $message->save();

        Log::info('创建了一条模板消息');
        return RJM(null, 1, '保存成功');
    }

    /**
     * 发送模版消息
     */
    public function sendMessage(Request $request) {
        $message_id = $request->get('id');
        $message = Message::where('id', $message_id)->first();


    }

    /**
     * 更新消息
     */
    public function update(Request $request) {
        $id = $request->get('id');
        $title = $request->get('title');
        $informer = $request->get('informer');
        $content=  $request->get('content');

        $message = Message::where('id', $id)->first();
        $message->title = $title;
        $message->informer = $informer;
        $message->content = $content;
        $message->save();
        return RJM(null, 1, '保存成功');
    }


    public function show($id) {
        $message = Message::where('id', $id)->first();
        $message->view = ++$message->view;
        $message->save();
        return view('jxh.show', ['message' => $message]);
    }



    //图片上传


    public function upload(Request $request) {
        $img = $request->file('file');
        $path = $img->store('public/images');
        $url = asset('storage/' . substr($path, 7));
        $res = [
            'errno' => 0,
            'data' => [
                $url
            ]
        ];
        return response($res);
    }

    public function getMessage($id) {
        if (!$message = Message::where('id', $id)->first()) {
            return RJM(null, -1, '消息不存在');
        }

        return RJM(['message' => $message], 1, '查询成功');

    }

    public function getMessages() {
        $messages = Message::paginate(15);
        foreach ($messages->data as $item) {
            $item->content = trim_words($item->content, 100);
        }
        return RJM($messages, 1, '查询成功');
    }

    public function pre() {

    }

    public function sendAll() {

    }


}
