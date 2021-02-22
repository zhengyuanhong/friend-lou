<?php
/**
 * Created by PhpStorm.
 * User: zheng
 * Date: 2021/1/24
 * Time: 20:05
 */

namespace App\Utils;

class ErrorCode
{
    const SUCCESS = ['code' => '200', 'message' => 'success'];
    const TOKEN_EXPIRED = ['code' => 'TOKEN_EXPIRED', 'message' => 'token 过期'];
    const INVITE_CODE_EXPIRED = ['code' => 'INVITE_CODE_EXPIRED', 'message' => '邀请码已过期'];
    const GET_OPENID_ERROR = ['code' => 'GET_OPENID_ERROR', 'message' => '获取微信参数 错误'];
    const UN_AUTHORIZATION = ['code' => 'UN_AUTHORIZATION', 'message' => '没有携带令牌错误'];
    const USER_IS_NO_EXITS = ['code' => 'USER_IS_NO_EXITS', 'message' => '用户不存在'];
    const NO_PARAM_VALIDATE = ['code' => 'NO_PARAM_VALIDATE', 'message' => '缺少参数'];
    const UNAUTHORIZED = ['code' => 'UNAUTHORIZED', 'message' => '非法操作'];
    const MSG_EXISTS = ['code'=>'MSG_EXISTS','message'=>'已经通知了'];
    const SAME_USER = ['code'=>'SAME_USER','message'=>'这是你发送的欠条，点击无效'];
    const READY_JOIN = ['code'=>'READY_JOIN','message'=>'你已经加入'];
    const LOU_IS_EXITS = ['code'=>'LOU_IS_EXITS','message'=>'该欠条不存在'];
    const NO_MSG = ['code'=>'NO_MSG','message'=>'没有这条消息'];
    const BREAK_RULE_MSG = ['code'=>'BREAK_RULE_MSG','message'=>'备注内容含违规内容'];
    const ADD_SAME_USER = ['code'=>'SAME_USER','message'=>'不能添加自己'];
    const SAME_NAME = ['code'=>'SAME_NAME','message'=>'昵称和其他人重复了'];
}