<?php

namespace App\Http\Middleware;

use App\Model\WechatUser;
use App\Utils\ErrorCode;
use App\Utils\JwtUtil;
use Closure;
use Illuminate\Support\Facades\Log;

class ApiCheckMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $jwt = $request->header('AUTH-TOKEN');
        if (empty($jwt)) {
            Log::error('未携带header', $request->headers->all());
            return $this->response_error(ErrorCode::TOKEN_EXPIRED);
        }

        $result = JwtUtil::validateToken($jwt);
        if ($result === false) {
            Log::error('token 检查不通过', $request->headers->all());
            return $this->response_error(ErrorCode::TOKEN_EXPIRED);
        }

        $user = WechatUser::query()->where('openid', $result['openid'])->first();
        if (empty($user)) {
            Log::error('用户不存在', $request->headers->all());
            return $this->response_error(ErrorCode::USER_IS_NO_EXITS);
        }

        $request->user = $user;

        return $next($request);
    }

    public function response_error($error_code)
    {
        return response()->json([
            'message' => $error_code['message'],
            'code' => $error_code['code'],
            'time' => time()
        ]);
    }
}
