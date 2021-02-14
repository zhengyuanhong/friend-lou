<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\UserException;
use App\Http\Controllers\Controller;
use App\Http\Requests\UserInfoRequest;
use App\Http\Requests\WechatUserRequest;
use App\Http\Resources\UserRecordResource;
use App\Model\UserRecord;
use App\Model\WechatUser;
use App\Utils\ErrorCode;
use App\Utils\JwtUtil;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $validate = Validator::make($request->all(), [
            'name' => '',
            'id' => 'required',
            'avatar_url' => ''
        ]);

        $name = $request->input('name', '');
        $id = $request->input('id', '');
        $avatar_url = $request->input('avatar_url', '');

        if ($validate->fails()) {
            return $this->response_json(ErrorCode::SUCCESS);
        }

        $user = WechatUser::query()->find($id);
        if (empty($user)) {
            return $this->response_json(ErrorCode::USER_IS_NO_EXITS);
        }

        if (empty($user->name)) {
            $user->name = $name;
        }

        $user->avatar_url = $avatar_url;
        $user->save();

        return $this->response_json(ErrorCode::SUCCESS, $user->toArray());
    }

    public function setUserInfo(Request $request)
    {
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
        $user_id = $request->get('user_id');
        $user = null;
        if (empty($user_id)) {
            $user = $request->user;
        } else {
            $user = WechatUser::query()->find($user_id);
        }

        if (empty($user)) {
            return $this->response_json(ErrorCode::USER_IS_NO_EXITS);
        }

        return $this->response_json(ErrorCode::SUCCESS, $user->toArray());
    }

    public function refreshToken(Request $request)
    {
        $jwt = $request->input('token', '');
        if (empty($jwt)) {
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }
        $data['token'] = JwtUtil::refreshToken($jwt);
        return $this->response_json(ErrorCode::SUCCESS, $data);
    }

    public function saveOtherUser(Request $request)
    {
        $other_user_id = $request->get('other_user_id');
        if (empty($other_user_id)) {
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }

        if (!WechatUser::query()->find($other_user_id)) {
            return $this->response_json(ErrorCode::USER_IS_NO_EXITS);
        }

        $user = $request->user;
        if ($user->id == $other_user_id) {
            return $this->response_json(ErrorCode::ADD_SAME_USER);
        }

        UserRecord::saveUser($user, $other_user_id);

        return $this->response_json(ErrorCode::SUCCESS);
    }

    public function otherUser(Request $request)
    {
        $user = $request->user;
        $other_user = $user->record()->orderBy('created_at', 'desc')->paginate(20);
        return UserRecordResource::collection($other_user);
    }
}
