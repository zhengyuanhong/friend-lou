<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Config
 * @package App\Model
 * @var string slogan 标语
 * @var string mini_app_appid 小程序appid
 * @var string mini_app_name  小程序名称
 * @var string mini_app_path   小程序路径
 * @var string admire_image   赞赏码图片
 * @var string admire_name  赞赏码
 */
class Config extends Model
{
    protected $table = 'config';
    protected $fillable = ['index', 'key', 'value'];

    static public function getIndexConifg()
    {
        $res = Config::query()->where('type', 'index')->select('key', 'value')->get()->toArray();
        $data = [];

        if (empty($res)) {
            self::createData();
        } else {
            foreach ($res as $v) {
                $data[$v['key']] = $v['value'];
            }
        }
        return $data;
    }

    static public function createData()
    {
        self::query()->insert(self::defaultData());
    }

    static function defaultData()
    {
        return [
            ['key' => 'slogan', 'value' => '', 'type' => 'index','description'=>'标语'],
            ['key' => 'mini_app_appid', 'value' => '', 'type' => 'index','description'=>'小程序appid'],
            ['key' => 'mini_app_path', 'value' => '', 'type' => 'index','description'=>'小程序路径'],
            ['key' => 'mini_app_name', 'value' => '', 'type' => 'index','description'=>'小程序名称'],
            ['key' => 'admire_image', 'value' => '', 'type' => 'index','description'=>'赞赏码图片'],
            ['key' => 'admire_name', 'value' => '', 'type' => 'index','description'=>'赞赏码名称'],
        ];
    }
}
