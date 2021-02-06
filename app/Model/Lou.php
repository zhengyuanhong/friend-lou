<?php

namespace App\Model;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lou extends Model
{
    use SoftDeletes;
    protected $table = 'user_lou';

    const CREATING = 'CREATING';
    const JIE_LOU = 'JIE_LOU';
    const JIE_LOU_OK = 'JIE_LOU_OK';
    const QIAN_LOU = 'QIAN_LOU';
    const QIAN_LOU_OK = 'QIAN_LOU_OK';

    static public $statusMap = [
        self::CREATING => 0,
        self::JIE_LOU => 1,
        self::JIE_LOU_OK => 2,
        self::QIAN_LOU => 1,
        self::QIAN_LOU_OK => 2,
    ];


    protected $fillable = ['creditors_user_id', 'creator', 'debts_user_id', 'amount', 'note', 'status', 'repayment_at', 'duration'];

    public function getCreatedAtAttribute($time)
    {
        return date('Y-m-d', strtotime($time));
    }

    public function getRepaymentAtAttribute($time)
    {
        return date('Y-m-d', strtotime($time));
    }

    public function louJiebeLongsToUser()
    {
        return $this->belongsTo(WechatUser::class, 'creditors_user_id', 'id');
    }

    public function louQianBelongsToUser()
    {
        return $this->belongsTo(WechatUser::class, 'debts_user_id', 'id');
    }

    public function louMessage(){
        return $this->hasOne(Message::class,'lou_id','id');
    }

    static public function diffTime($repayment_at){
        $start = strtotime($repayment_at);
        $now = time();
        if($start < $now){
            return 'overdue';
        }
        //离还款还差几天
        $now = Carbon::createFromTimestamp($now);
        return $now->diffInDays(Carbon::createFromTimestamp($start));
    }

    public function message(){
        return $this->hasOne(Message::class,'lou_id','id');
    }

    public function bindMessage(){
        return $this->hasOne(Message::class,'lou_id','id')->where('type','bind');
    }
}
