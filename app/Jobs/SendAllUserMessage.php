<?php

namespace App\Jobs;

use App\Message;
use App\Notifications\TemplateMessage;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

class SendAllUserMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $message;

    public $templateConfig;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Message $message, array $config)
    {
        $this->message = $message;

        $this->templateConfig = $config;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $config = [
            'template_id' => $this->templateConfig['template_id'],
            // todo url
            'url' => url('message/show', [$this->message->id]),
            'data' => [
                'first' => [$this->message->title, '#15b3fa'],
                'keyword1' => '浙江工业大学',
                'keyword2' => $this->message->informer,
                'keyword3' => date('Y-m-d H:i:s', time()),
                'keyword4' => [$this->message->notify_content, '#F05837'],
                'remark' => '点击查看详情'
            ]
        ];
        Log::info(['config' => $config]);
        $users = User::all();
        foreach ($users as $user) {
            if ($user->allow_send) {
                $user->notify(new TemplateMessage($config));
            }
        }

    }
}
