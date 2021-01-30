<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LouRequest;
use App\Http\Resources\LouResource;
use App\Model\Lou;
use App\Model\Message;
use App\Utils\ErrorCode;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class LouController extends Controller
{

    public function create(Request $request)
    {
        $input = $request->all();
        $validator = Validator::make($input, [
            'amount' => 'required',
            'duration' => 'required',
            'lou_type' => 'required',
            'note' => ''
        ]);

        if($validator->fails()){
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }

        $fill_arr = [
            'amount' => $input['amount'],
            'note' => isset($input['note']) ? $input['note'] : "暂无",
            'status' => Lou::$statusMap['CREATING'],
            'creator' => $request->user->id,
            'repayment_at' => time() + $input['duration'],
            'duration' => $input['duration']
        ];
        if ($input['lou_type'] == 'lou_jie') {
            $fill_arr['creditors_user_id'] = $request->user->id;
        } elseif ($input['lou_type'] == 'lou_qian') {
            $fill_arr['debts_user_id'] = $request->user->id;
        }

        $lou = null;
        Cache::lock('lock')->get(function () use ($fill_arr, &$lou) {
            $lou = Lou::query()->create($fill_arr);
        });

        $data = $lou->toArray();

        //TODO 添加延时队列（30分钟后作废）
        return $this->response_json(ErrorCode::SUCCESS, $data);
    }

    public function creatingLous(Request $request)
    {
        $status = $request->get('status');
        $user = $request->user;
        $query = Lou::query()->where('status', Lou::$statusMap[$status]);
        switch ($status) {
            case 'CREATING':
                //创建中
                $query = $query->where('creator', $user->id);
                break;
            case 'JIE_LOU':
                //你借给ta
                $query = $query->where('creditors_user_id', $user->id);
                break;
            case 'JIE_LOU_OK':
                //已收款
                $query = $query->where('creditors_user_id', $user->id);
                break;
            case 'QIAN_LOU':
                //你的欠条
                $query = $query->Where('debts_user_id', $user->id)
                    ->whereNotNull('creditors_user_id');
                break;
            //已还清
            case 'QIAN_LOU_OK':
                $query = $query->where('debts_user_id', $user->id)
                    ->whereNotNull('creditors_user_id');
                break;
        }
        $lou = $query->paginate(10);
        return LouResource::collection($lou);
    }

    public function oneLou(Request $request)
    {
        $lou_id = $request->get('lou_id');
        if (empty($lou_id)) {
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }
        $lou = Lou::query()->find($lou_id);
        if (empty($lou)) {
            return $this->response_json(ErrorCode::LOU_IS_EXITS);
        }

        return $this->response_json(ErrorCode::SUCCESS, $lou->toArray());

    }

    public function operateLou(Request $request)
    {
        $lou = Lou::query()->find($request->get('lou_id'));
        $user = $request->user;
        $this->is_user($user, $lou);
        $operate_type = $request->get('operate_type');
        //债主才能有删除，和修改的权力
        switch ($operate_type) {
            case 'ok':
                $lou->status = Lou::$statusMap['JIE_LOU_OK'];
                $lou->save();
                break;
            case 'delete':
                $lou->delete();
                break;
        }
        return $this->response_json(ErrorCode::SUCCESS, $lou->toArray());
    }

    public function invite(Request $request)
    {
        $lou_id = $request->get('lou_id');
        if (empty($lou_id)) {
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }

        //生成20分钟有效的邀请码
        $data['invite_code'] = $this->makeInviteCode($lou_id);

        return $this->response_json(ErrorCode::SUCCESS, $data);
    }

    public function join(Request $request)
    {
        $invite_code = $request->get('invite_code');

        $lou_id = Cache::get($invite_code);
        if (empty($lou_id)) {
            return $this->response_json(ErrorCode::INVITE_CODE_EXPIRED);
        }

        $user = $request->user;
        $lou = Lou::query()->find($lou_id);
        Log::info('借条信息:', $lou->toArray());

        if (empty($lou)) {
            return $this->response_json(ErrorCode::LOU_IS_EXITS);
        }

        if ($lou->status >= Lou::$statusMap['QIAN_LOU'] || $lou->status >= Lou::$statusMap['JIE_LOU']) {
            return $this->response_json(ErrorCode::READY_JOIN);
        }

        //如果借款的id为空，就判断欠款人是否存在，并不等于操作人的id
        if (empty($lou->creditors_user_id)) {
            if ($lou->debts_user_id == $user->id) {
                return $this->response_json(ErrorCode::SAME_USER);
            }

            $lou->creditors_user_id = $user->id;
            $lou->status = Lou::$statusMap['JIE_LOU'];
        }
        //如果欠款的id为空
        if (empty($lou->debts_user_id)) {
            if ($lou->creditors_user_id == $user->id) {
                return $this->response_json(ErrorCode::SAME_USER);
            }

            $lou->debts_user_id = $user->id;
            $lou->status = Lou::$statusMap['QIAN_LOU'];
        }
        $lou->save();

        //删除邀请码
        Cache::forget($invite_code);
        return $this->response_json(ErrorCode::SUCCESS);
    }

    public function is_user($user, $lou)
    {
        if ($user->id != $lou->creditors_user_id || $user->id != $lou->debts_user_id) {
            $this->response_json(ErrorCode::UNAUTHORIZED);
        }
    }

    public function makeInviteCode($lou_id)
    {
        $key = 'invite-code:' . uniqid();
        Cache::put($key, $lou_id, Carbon::now()->addMinutes(20));
        return $key;
    }
}


