<?php

namespace App\Console\Commands;

use App\Jobs\AsyncUnionId;
use Illuminate\Console\Command;

class AsyncWechatOpenid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'async:openid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '同步所有用户的openid';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userService = app('wechat')->user;
        $nextOpenid = null;
        $count = 0;
        do {
            $result = $userService->list($nextOpenid);
            $nextOpenid = $result->next_openid;
            $data = $result->data;
            $list = $data['openid'];
            if (count($list) > 0) {
                foreach ($list as $key => $value) {
                    AsyncUnionId::dispatch($value);
                    echo ++$count . "\n";
                }
            }

        } while ($nextOpenid);
        echo '同步开始' . $count . "个\n";

    }
}
