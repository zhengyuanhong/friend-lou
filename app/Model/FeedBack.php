<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class FeedBack extends Model
{
    protected $table = 'feed_back';

    protected $fillable = ['content','user_id'];
}
