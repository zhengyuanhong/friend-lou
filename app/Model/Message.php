<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Message extends Model
{
    use SoftDeletes;
    protected $table = 'message';

    public static $statusMap = [
        'is_read' => 1,
        'no_read' => 0,
        'no' => 3,//拒绝
        'yes' => 2,//接受
    ];

    protected $fillable = ['user_id', 'lou_id', 'title', 'content', 'is_read', 'type'];

    function getCreatedAtAttribute($value)
    {
        return date('m-d',strtotime($value));
    }

    public function lou()
    {
        return $this->belongsTo(Lou::class, 'lou_id', 'id');
    }

    static public function changeIsRead($lou_id, $type, $is_read)
    {
        self::query()
            ->where('lou_id', $lou_id)
            ->where('type', $type)
            ->update(['is_read' => $is_read]);
    }
}
