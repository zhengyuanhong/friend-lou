<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class RepaymentTimeConfig extends Model
{
    protected $table = 'repayment_time_config';
    static public function getConfig(){
        $res = self::query()->select('day','description')->get();
        $data = [];
        $day = [];
        $description = [];
        foreach ($res as $v){
            $day[] = $v['day'];
            $description[] = $v['description'];
        }
        $data['day'] = $day;
        $data['description'] = $description;
        return $data;
    }
}
