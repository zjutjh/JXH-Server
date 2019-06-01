<?php

namespace App\Jobs;

use App\Notifications\TemplateMessage;
use App\Services\FaceMergeServices;
use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Intervention\Image\ImageManager;

class GetByz implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    public $user;

    public $zjz;

    private $faceService;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $zjz)
    {
        //
        $this->faceService = new FaceMergeServices(config('face.appkey'), config('face.appsecret'));
        $this->user;
        $this->zjz = $zjz;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $byz = $this->faceService->getByz();


        $image = new ImageManager(array('driver' => 'imagick'));
        $img = $image->make($byz);

        $img->text("朱兴照", 470, 1020, function($font) {
            $font->file('/var/www/html/jxh-server/storage/app/public/font.ttf');
            $font->size(60);
        });
        $img->text("安全工程", 280, 1110, function($font) {
            $font->file('/var/www/html/jxh-server/storage/app/public/font.ttf');
            $font->size(60);
        });


        $this->user->notify(new TemplateMessage($this->getConfig('')));
        usleep(300);
    }


    private function getConfig($url)
    {
        return array(
            'template_id' => config('templatemsg.message.template_id'),
            'url' => url('message/show', ['']),
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
