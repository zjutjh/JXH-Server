<?php

namespace App\Jobs;

use GuzzleHttp\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class RedirectWxToBk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $data;

    public $mthod;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data, $method)
    {
        $this->data = $data;
        $this->mthod = $method;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $url = 'https://paas.jh.zjutjh.com/console/user_center/weixin/mp/callback/?';
        $client = new Client();
        if ($this->mthod === 'GET') {
            $client->get($url.http_build_query($this->data));
        } else {
            $client->post($url, [
                'data' => $this->data
            ]);
        }
    }
}
