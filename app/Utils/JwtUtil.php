<?php
/**
 * Created by PhpStorm.
 * User: zheng
 * Date: 2021/1/23
 * Time: 20:13
 */
namespace App\Utils;

use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class JwtUtil{
    const LEEWAY = 60;
    const DURATION = 31536000; // 86400 * 365 = 31536000

    static public function getToken($openid){
        $payload = array(
            "iat" => time(),
            "exp"=>time()+self::DURATION,
            "openid"=>$openid
        );

        $token =  JWT::encode($payload,env('JWT_KEY','default'));
        Cache::put(self::cacheKey($openid),$token,self::DURATION);
        Log::info('openid：'.$openid.'获取token：'.$token);
        return $token;
    }

    static public function refreshToken($jwt){
        $key = env('JWT_KEY');
        $decode = JWT::decode($jwt,$key,['HS256']);
        $arr_decode = (array)$decode;
        $token = self::getToken($arr_decode['openid']);
        return $token;
    }

    static public function cacheKey($openid){
        return 'auth'.$openid;
    }

    static public function validateToken($jwt){
        JWT::$leeway = self::LEEWAY;
        $key = env('JWT_KEY');

        try{
           $decode = JWT::decode($jwt,$key,['HS256']);
           $arr_decode = (array)$decode;
           $cacheJwt = Cache::get(self::cacheKey($arr_decode['openid']));

           if($jwt !== $cacheJwt){
               Log::warning('jwt不一致',[
                   'jwt'=>$jwt,
                   'cache_jwt'=>$cacheJwt,
                   'arr_decode'=>$arr_decode
               ]);
               return false;
           }
        }catch (\Exception $e){
            Log::warning('jwt检查不通过',[
                'jwt'=>$jwt,
                'exception_message'=>$e->getMessage()
            ]);
            return false;
        }

        return $arr_decode;
    }
}