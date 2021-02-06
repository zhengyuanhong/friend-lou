<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';

    public static $statusMap = [
        'is_read' => 1,
        'no_read' => 0,
        'no' =>3,//拒绝
        'yes' =>2,//接受
    ];

    protected $fillable = ['user_id','lou_id','title','content','is_read','type'];

    function  getCreatedAtAttribute($value)
    {
        return date('H:m',strtotime($value));
    }

    public function lou(){
        return $this->belongsTo(Lou::class,'lou_id','id');
    }
}
