<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{
    protected $table = 'images';

    public function getPathAttribute($url)
    {
        $arr = explode('://', $url);
        if (in_array('https', $arr) || in_array('http', $arr)) {
            return $url;
        } else {
            return env('APP_URL') . '/storage/' . $url;
        }
    }
}
