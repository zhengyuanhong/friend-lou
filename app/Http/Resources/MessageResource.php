<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
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
            'user_id' => $this->user_id,
            'lou_id' => $this->lou_id,
            'is_read'=>$this->is_read,
            'type' => $this->type,
            'title' => $this->title,
            'content' => $this->content,
            'created_at' => $this->created_at
        ];
    }
}
