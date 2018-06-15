<?php

namespace App\Providers;

use EasyWeChat\Factory;
use EasyWeChat\OfficialAccount\Application;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Cache\Simple\RedisCache;

class WeChatServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('wechat', function($app) {
            $predis = app('redis')->connection()->client();
            $cacheDriver = new RedisCache($predis);
            $wechat = Factory::officialAccount(config('wechat'));
            $wechat['cache'] = $cacheDriver;
            return $wechat;
        });
    }
}
