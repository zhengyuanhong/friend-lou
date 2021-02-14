<?php

namespace App\Http\Resources;

use App\Model\Lou;
use Illuminate\Http\Resources\Json\JsonResource;

class LouResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {

        return [
            'id' => $this->id,
            'creditors_user_id' => $this->creditors_user_id,
            'debts_user_id' => $this->debts_user_id,
            $this->mergeWhen(!empty($this->creditors_user_id), [
                'lou_jie_name' => empty($this->creditors_user_id) ? "" : $this->louJiebeLongsToUser->name,
                'jie_user_id' => empty($this->creditors_user_id) ? "" : $this->louJiebeLongsToUser->id,
            ]),
            $this->mergeWhen(!empty($this->debts_user_id), [
                'lou_qian_name' => empty($this->debts_user_id) ? "" : $this->louQianbeLongsToUser->name,
                'qian_user_id' => empty($this->debts_user_id) ? "" : $this->louQianbeLongsToUser->id,
            ]),
            'message' => empty($this->bindMessage) ? false : $this->bindMessage, //获取绑定消息
            'amount' => $this->amount,
            'note' => $this->note,
            'is_notify' => empty($this->louMessage) ? false : true, //是否发送通知消息
            'status' => $this->status,
            'duration' => $this->duration,
            'duration_day' => Lou::diffTime($this->repayment_at),
            'repayment_at' => $this->repayment_at,
            'created_at' => $this->created_at
        ];
    }
}
