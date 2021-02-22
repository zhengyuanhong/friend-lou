<?php
namespace App\Services;

use App\Model\Lou;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

class LouService{
    public function createLou($request,$input,$message){
        $fill_arr = [
            'amount' => $input['amount'],
            'note' => isset($input['note']) ? $input['note'] : "暂无",
            'status' => Lou::$statusMap['CREATING'],
            'creator' => $request->user->id,
            'repayment_at' => Carbon::now()->addDays($input['duration']),
            'duration' => $input['duration']
        ];
        //借条
        if ($input['lou_type'] == 'lou_jie') {
            //借款者
            $fill_arr['creditors_user_id'] = $request->user->id;
            if (!empty($input['other_user_id'])) {
                //欠款者
                $fill_arr['debts_user_id'] = $input['other_user_id'];
            }
            //欠条
        } elseif ($input['lou_type'] == 'lou_qian') {
            //欠款者
            $fill_arr['debts_user_id'] = $request->user->id;
            if (!empty($input['other_user_id'])) {
                //借款者
                $fill_arr['creditors_user_id'] = $input['other_user_id'];
            }
        }

        $lou = null;
        Cache::lock('lock')->get(function () use ($fill_arr, &$lou) {
            $lou = Lou::query()->create($fill_arr);
        });


        if (!empty($input['other_user_id'])) {
            //借款者和欠款者都到位，发送信息
            $message->createMsg($request, $lou, 'bind');
        }

        return $lou->toArray();
    }
}
