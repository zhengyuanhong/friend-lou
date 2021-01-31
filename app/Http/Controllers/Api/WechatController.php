<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInfoRequest;
use App\Http\Requests\WechatUserRequest;
use App\Model\WechatUser;
use App\Utils\ErrorCode;
use App\Utils\JwtUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class WechatController extends Controller
{

    public function login(WechatUserRequest $request)
    {
        $code = $request->input('code', '');
        $app = app('easyWechat');
        $res = $app->auth->session($code);

        if (isset($res['errcode'])) {
            $error = ErrorCode::GET_OPENID_ERROR;
            Log::error('获取openid 失败', $error);
            throw new UserException($error['message'], $error['code']);
        }

        $token = Cache::get(JwtUtil::cacheKey($res['openid'])) ?: JwtUtil::getToken($res['openid']);

        $user = WechatUser::query()->where('openid', $res['openid'])->first();
        if (empty($user)) {
            $user = new WechatUser();
            $user->openid = $res['openid'];
            $user->name = '';
            $user->save();
            Log::info('创建user数据');
        }

        $data = $user->toArray();
        $data['token'] = $token;
        return $this->response_json(ErrorCode::SUCCESS, $data);
    }

    public function putUserInfo(UserInfoRequest $request)
    {
        $name = $request->input('name', '');
        $id = $request->input('id', '');

        $user = WechatUser::query()->find($id);

        if (empty($user->name)) {
            $user->name = $name;
        }
        $user->save();

        return $this->response_json(ErrorCode::SUCCESS, $user->toArray());
    }
    public function setUserInfo(Request $request){
        $name = $request->input('name', '');
        $email = $request->input('email', '');
        $id = $request->input('id', '');

        $user = WechatUser::query()->find($id);
        if ($name) $user->name = $name;
        if ($email) $user->email = $email;
        $user->save();

        return $this->response_json(ErrorCode::SUCCESS, $user->toArray());
    }

    public function getUserInfo(Request $request)
    {
        $user = $request->user;
        return $this->response_json(ErrorCode::SUCCESS, $user->toArray());
    }

    public function refreshToken(Request $request)
    {
        $jwt = $request->input('token', '');
        if(empty($jwt)){
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }
        $data['token'] = JwtUtil::refreshToken($jwt);
        return $this->response_json(ErrorCode::SUCCESS, $data);
    }
}
