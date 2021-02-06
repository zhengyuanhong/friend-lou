<?php

namespace App\Utils;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class MsgCheckUtil
{
    static public function getAccessToken()
    {
        $key = config('miniWechat.mini_wechat.appid').'zheng';
        $CacheToken = Cache::get($key);
        if(!empty($CacheToken)){
           return $CacheToken;
        }

        $app = app('easyWechat');
        $token = $app->access_token->getToken();
        Log::info('access_token>>>',$token);
        Cache::put($key,$token['access_token'],Carbon::now()->addMinutes(120));
        return $token['access_token'];
    }

    static public function checkRequest($content)
    {
        $access_token = self::getAccessToken();
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_URL => "https://api.weixin.qq.com/wxa/msg_sec_check?access_token={$access_token}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode(['content'=>$content],JSON_UNESCAPED_UNICODE),
            CURLOPT_HTTPHEADER => [
                "Postman-Token: b8f0e8d8-0890-4898-8c01-3e0aa328f91a",
                "cache-control: no-cache"
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            echo "cURL Error #:" . $err;
            Log::error('检查内容，请求错误>>>',$err);

            return false;
        }
        $arr = json_decode($response,true);
        Log::info('检查内容，请求成功>>>',$arr);
        if($arr['errmsg'] != 'ok'){
           return false;
        }
        return true;
    }
}
