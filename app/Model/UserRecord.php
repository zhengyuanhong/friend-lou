<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class UserRecord extends Model
{
    protected $table = 'user_record';
    protected $fillable = ['user_id', 'other_user_id'];

    public function user()
    {
        return $this->belongsTo(WechatUser::class, 'user_id', 'id');
    }

    public function otherUser()
    {
        return $this->belongsTo(WechatUser::class, 'other_user_id', 'id');
    }

    static function saveUser($user, $other_user_id)
    {
        $record1 = self::query()
            ->where('user_id', $user->id)
            ->where('other_user_id', $other_user_id)
            ->first();

        $record2 = self::query()
            ->where('user_id', $other_user_id)
            ->where('other_user_id', $user->id)
            ->first();

        if (empty($record1)) {
            self::query()->create(
                [
                    'user_id' => $user->id,
                    'other_user_id' => $other_user_id
                ]
            );
        }

        if (empty($record2)) {
            self::query()->create(
                [
                    'user_id' => $other_user_id,
                    'other_user_id' => $user->id
                ]
            );
        }

    }
}
