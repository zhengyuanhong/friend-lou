<?php

use Illuminate\Support\Facades\Route;

Route::prefix('v1')->namespace('Api')->group(function () {
    Route::put('login', 'WechatController@login');
    Route::post('refresh-token', 'WechatController@refreshToken');

    Route::middleware('api-check')->group(function () {
        //提交用户信息
        Route::put('user_info', 'WechatController@putUserInfo');
        //设置用户信息
        Route::put('set_user_info', 'WechatController@setUserInfo');
        //获取用户信息
        Route::get('user-info', 'WechatController@getUserInfo');
        //创建欠条
        Route::post('create_lou', 'LouController@create');
        //获取正在创建欠条
        Route::get('lous', 'LouController@getLous');
        //获取一张欠条
        Route::get('one_lou', 'LouController@oneLou');
        //操作欠条
        Route::post('operate_lou','LouController@operateLou');
        //绑定欠款人
        Route::post('bind_user','LouController@bindUser');
        //获取首页账单数据
        Route::get('index_data', 'IndexController@index');
        //消息通知
        Route::post('send_msg','MessageController@send');
        Route::put('read_message','MessageController@read');
        Route::get('one_message','MessageController@getOneMessage');
        //获取信息
        Route::get('get_msg','MessageController@messages');
        //获取邀请码
        Route::get('invite_code','LouController@invite');
        //加入
        Route::post('join_lou','LouController@join');
    });
});


