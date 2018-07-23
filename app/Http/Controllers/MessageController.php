<?php

namespace App\Http\Controllers;

use App\Jobs\SendAllUserMessage;
use App\Message;
use App\Notifications\TemplateMessage;
use App\Services\UserCenterService;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $notify_content = $request->get('notify_content');

        $message = new Message();
        $message->title = $title;
        $message->informer = $informer;
        $message->content = $content;
        $message->notify_content = $notify_content;
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
        $notify_content = $request->get('notify_content');


        $message = Message::where('id', $id)->first();
        $message->title = $title;
        $message->informer = $informer;
        $message->content = $content;
        $message->notify_content = $notify_content;

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
        $messages = Message::orderBy('id', 'desc')->paginate(15);
        foreach ($messages as $item) {
            $item->content = trim_words($item->content, 100);
        }
        return RJM($messages, 1, '查询成功');
    }

    public function pre($id) {
        $message = Message::where('id', $id)->first();
        Log::info(['show' => url('message/show', [$id])]);
        $config = [
            'template_id' => config('templatemsg.message.template_id'),
            // todo url
            'url' => url('message/show', [$id]),
            'data' => [
                'first' => [$message->title, '#05328E'],
                'keyword1' => '浙江工业大学',
                'keyword2' => $message->informer,
                'keyword3' => date('Y-m-d H:i:s', time()),
                'keyword4' => [$message->notify_content, '#4D0015'],
                'remark' => '点击查看详情'
            ]
        ];
        $user = Auth::user();
        $user->notify(new TemplateMessage($config));
        return RJM(null, 1, '预览已经发送');
    }

    public function sendAll(Request $request, $id) {
        $username = $request->get('username');
        $passwd = $request->get('passwd');
        $uCenter = new UserCenterService();

        if (!$error = $uCenter->checkJhPassport($username, $passwd)) {
            $error = $uCenter->getError();
            return RJM(null, -1,  $error ? $error : '用户名或密码错误');
        }

        $user =  User::where('sid', $username)->first();
        if (!$user->isAdmin()) {
            return RJM(null, -1, '权限不足');
        }


        $message = Message::where('id', $id)->first();
        if ($message->is_send) {
            return RJM(null, -1, '模板消息已经发送过');

        }

        $admin_user = User::where('user_type', 2)->first();
        $messageUid = create_messageUid($id);
        $admin_user->notify(new TemplateMessage(create_to_super_admin_config($messageUid)));

        return RJM(null, 1, '等待管理员同意');
    }


}
