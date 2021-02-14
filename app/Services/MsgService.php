<?php

namespace App\Services;

use App\Model\Lou;
use App\Model\Message;
use App\Model\WechatUser;
use Illuminate\Http\Request;

class MsgService
{
    public function createMsg(Request $request, Lou $lou = null, $type = 'lou')
    {
        switch ($type) {
            case 'lou':
                $title = '已还还款提醒通知';
                $content = '【' . $lou->louQianBelongsToUser->name . '】已还款【' . $lou->amount . '】元，处理一下【' . $lou->louQianBelongsToUser->name . '】的欠条';
                $user_id = $lou->louJiebeLongsToUser->id;
                break;

            case 'bind':
                $title = '绑定欠条通知';
                if ($request->user->id == $lou->louJiebeLongsToUser->id) {
                    $content = '你在【' . $lou->created_at . '】向【' . $lou->louJiebeLongsToUser->name . '】借了【' . $lou->amount . '】元，是否同意这张欠条？';
                    $user_id = $lou->louQianBelongsToUser->id;
                }
                if ($request->user->id == $lou->louQianBelongsToUser->id) {
                    $content = '【' . $lou->louQianBelongsToUser->name . '】 在【' . $lou->created_at . '】向你借了【' . $lou->amount . '】元，是否同意这张欠条？';
                    $user_id = $lou->louJiebeLongsToUser->id;
                }
                break;

        }

        Message::query()->create([
            'title' => $title,
            'content' => $content,
            'user_id' => $user_id,
            'type' => $type,
            'lou_id' => $lou->id,
            'is_read' => Message::$statusMap['no_read']
        ]);
    }

    public function createTemplateMsg(Lou $lou = null, $type = 'overdue', $overdue = 0)
    {
        switch ($type) {
            case 'repayment':
                $title = '账单通知';
                $content = sprintf('【还款金额】：%s；【还款时间】：%s；【备注】：%s；', $lou->amount, $lou->repayment_at, $lou->note ?: '暂无');
                $user_id = $lou->louQianBelongsToUser->id;
                break;

            case 'overdue':
                $title = '欠账到期提醒';
                $content = sprintf('【姓名】：%s；【金额】：%s；【日期】：%s；【类型】：%s；【备注】：%s；', $lou->louQianBelongsToUser->name, $lou->amount, $lou->repayment_at, '欠条', $overdue);
                $user_id = $lou->louQianBelongsToUser->id;
                break;
        }

        Message::query()->create([
            'title' => $title,
            'content' => $content,
            'user_id' => $user_id,
            'type' => $type,
            'lou_id' => $lou->id,
            'is_read' => Message::$statusMap['no_read']
        ]);
    }

    public function _timeFormat($time)
    {
        return implode('-', $time);
    }
}
