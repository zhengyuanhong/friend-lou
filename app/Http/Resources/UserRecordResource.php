<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserRecordResource extends JsonResource
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
            'other_user_id' => $this->other_user_id,
            'other_user_name' => $this->otherUser->name,
            'other_user_avatar_url' => $this->otherUser->avatar_url,
            'other_user_unique_id' => $this->otherUser->unique_id,
            'other_user_credit_score'=>$this->otherUser->credit_score,
            'other_user_keep_promise'=>$this->otherUser->keep_promise,
            'other_user_break_promise'=>$this->otherUser->break_promise,
        ];
    }
}
