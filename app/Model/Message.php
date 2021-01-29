<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'message';

    public static $statusMap = [
        'is_read' => 1,
        'no_read' => 0
    ];

    protected $fillable = ['user_id','lou_id','content','is_read','type'];
}
