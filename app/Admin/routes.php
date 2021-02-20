<?php

use App\Admin\Forms\Setting;
use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix' => config('admin.route.prefix'),
    'namespace' => config('admin.route.namespace'),
    'middleware' => config('admin.route.middleware'),
    'as' => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('wechat-users', WechatUserController::class);
    $router->resource('lous', LouController::class);
    $router->resource('configs', ConfigController::class);
    $router->resource('messages', MessageController::class);
    $router->resource('banners', BannerController::class);
});
