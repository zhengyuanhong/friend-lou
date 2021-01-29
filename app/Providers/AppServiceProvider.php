<?php

namespace App\Providers;

use EasyWeChat\Factory;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
         $this->app->singleton('easyWechat',function(){
             $config = [
                 'app_id' => config('miniWechat.mini_wechat.appid'),
                 'secret' => config('miniWechat.mini_wechat.secret'),

                 // 下面为可选项
                 // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
                 'response_type' => 'array',

                 'log' => [
                     'level' => 'debug',
                     'file' => storage_path('/logs/wechat.log'),
                 ],
             ];

            return Factory::miniProgram($config);
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        JsonResource::withoutWrapping();
    }
}
