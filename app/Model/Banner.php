<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banner';

    public function getUrlAttribute($url)
    {
        $arr = explode('://', $url);
        if (in_array('https', $arr) || in_array('http', $arr)) {
            return $url;
        } else {
            return env('APP_URL') . '/storage/' . $url;
        }
    }
}
