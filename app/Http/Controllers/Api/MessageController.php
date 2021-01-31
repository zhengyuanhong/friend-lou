<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MsgRequest;
use App\Http\Resources\MessageResource;
use App\Model\Lou;
use App\Model\Message;
use App\Utils\ErrorCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MessageController extends Controller
{
    public function send(Request $request)
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

        $content = '对方已还款【'.$lou->amount.'】元，处理一下【' . $lou->louQianBelongsToUser->name . '】的欠条';
        Message::query()->create([
            'content' => $content,
            'user_id' => $lou->louJieBelongsToUser->id,
            'type' => 'lou',
            'lou_id' => $lou->id,
            'is_read' => Message::$statusMap['no_read']
        ]);

        return $this->response_json(ErrorCode::SUCCESS);
    }

    public function messages(Request $request){
        $user = $request->user;
        $message = $user->messages()->orderBy('created_at','DESC')->orderBy('is_read')->paginate(20);
        return MessageResource::collection($message);
    }

    public function read(MsgRequest $request){
        $msg_id = $request->get('msg_id');
        $msg = Message::query()->find($msg_id);
        if(empty($msg)){
           return $this->response_json(ErrorCode::NO_MSG);
        }

        $msg->is_read = Message::$statusMap['is_read'];
        $msg->save();

        return $this->response_json(ErrorCode::SUCCESS);
    }

    public function is_user($user, $lou)
    {
        if ($user->id != $lou->creditors_user_id || $user->id != $lou->debts_user_id) {
            $this->response_json(ErrorCode::UNAUTHORIZED);
        }
    }
}
