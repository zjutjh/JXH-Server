<?php

namespace App\Jobs;

use App\Notifications\TemplateMessage;
use App\Services\FaceMergeServices;
use App\User;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Redis;
use Intervention\Image\ImageManager;

class GetByz implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $openid;

    public $data;

    private $faceService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($openid, $data)
    {
        //
        $this->openid = $openid;
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $this->faceService = new FaceMergeServices(config('face.appkey'), config('face.appsecret'));
        $byz = $this->faceService->getByz($this->data['img'], $this->data['sex']);


        $image = new ImageManager(array('driver' => 'imagick'));
        $img = $image->make($byz);

        $img->text($this->data['name'], 470, 1020, function($font) {
            $font->file('/var/www/html/jxh-server/storage/app/public/font.ttf');
            $font->size(60);
        });
        $img->text($this->data['major'], 280, 1110, function($font) {
            $font->file('/var/www/html/jxh-server/storage/app/public/font.ttf');
            $font->size(60);
        });

        $img->save('/var/www/html/jxh-server/storage/app/public/show/'.date("YmdHis", time()).rand(1000, 9999).".jpg");
        $imgUrl = url('storage/show/'.$img->basename);
        $hashId= encrypt($this->openid);
        $user = User::where('openid', $this->openid)->first();
        Redis::set('img.'. $hashId, $imgUrl);
        $user->notify(new TemplateMessage($this->getConfig($hashId)));
        usleep(300);
    }


    private function getConfig($id)
    {
        return array(
            'template_id' => config('templatemsg.message.template_id'),
            'url' => url('jxh/byz/show', [$id]),
            'data' => [
                'first' => ['', '#05328E'],
                'keyword1' => '浙江工业大学',
                'keyword2' => '浙江工业大学学工部 精弘网络',
                'keyword3' => date('Y-m-d H:i:s', time()),
                'keyword4' => ['', '#4D0015'],
                'remark' => '点击查看详情'
            ]
        );
    }
}
