<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MsgRequest;
use App\Http\Resources\MessageResource;
use App\Model\Lou;
use App\Model\Message;
use App\Services\MsgService;
use App\Utils\ErrorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class MessageController extends Controller
{
    public function send(Request $request,MsgService $message)
    {
        //欠债的人只能发信息通知
        $lou_id = $request->get('lou_id');

        $user = $request->user;

        /** @var Lou $lou */
        $lou = Lou::query()->find($lou_id);
        $this->is_user($user, $lou);

        if ($lou->louMessage()->exists()) {
            return $this->response_json(ErrorCode::MSG_EXISTS);
        }

        $message->createMsg($request,$lou,'lou');

        return $this->response_json(ErrorCode::SUCCESS);
    }

    public function messages(Request $request)
    {
        $user = $request->user;
        $message = $user->messages()->orderBy('created_at', 'DESC')->orderBy('is_read')->paginate(20);
        return MessageResource::collection($message);
    }

    public function getOneMessage(Request $request)
    {
        $msg_id = $request->get('msg_id');
        if (empty($msg_id)) {
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }

        $msg = Message::query()->find($msg_id);
        if (empty($msg)) {
            return $this->response_json(ErrorCode::NO_MSG);
        }

        return $this->response_json(ErrorCode::SUCCESS, $msg->toArray());
    }

    public function read(MsgRequest $request)
    {
        $msg_id = $request->get('msg_id');
        $input = $request->all();
        $validate = Validator::make($input, [
            'msg_id' => 'required',
            'operate_type' => ''
        ]);
        if ($validate->fails()) {
            return $this->response_json(ErrorCode::NO_PARAM_VALIDATE);
        }

        $msg = Message::query()->find($msg_id);
        if (empty($msg)) {
            return $this->response_json(ErrorCode::NO_MSG);
        }

        $msg->is_read = Message::$statusMap['is_read'];

        if (!empty($input['operate_type'])) {
            if ($msg->type == 'bind') {
                if ($input['operate_type'] == 'yes') {
                    $msg->is_read = Message::$statusMap['yes'];
                    //只要状态为1就行
                    $msg->lou->update(['status'=>Lou::$statusMap['JIE_LOU']]);
                } elseif ($input['operate_type'] == 'no') {
                    $msg->is_read = Message::$statusMap['no'];
                    $msg->lou->update(['status'=>Lou::$statusMap['CREATING']]);
                }
            }
        }

        $msg->save();

        return $this->response_json(ErrorCode::SUCCESS,$msg->toArray());
    }

    public function is_user($user, $lou)
    {
        if ($user->id != $lou->creditors_user_id || $user->id != $lou->debts_user_id) {
            $this->response_json(ErrorCode::UNAUTHORIZED);
        }
    }
}
