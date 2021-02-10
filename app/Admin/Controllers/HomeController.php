<?php

namespace App\Admin\Controllers;

use App\Admin\Forms\Setting;
use App\Http\Controllers\Controller;
use App\Model\Config;
use App\Model\Lou;
use App\Model\WechatUser;
use Encore\Admin\Grid;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\Form;
use Encore\Admin\Widgets\InfoBox;

class HomeController extends Controller
{
    public function index(Content $content)
    {
        $data = $this->total();

        return $content
            ->title('Dashboard')
            ->header('好友欠条')
            ->description('数据预览')
            ->row(function (Row $row) use($data) {
                $row->column(3, new InfoBox('用户数','user','green','/admin/wechat-users',$data['users']));
                $row->column(3, new InfoBox('创建中','clone','red','/admin/lous',$data['creating']));
                $row->column(3, new InfoBox('已还清','clone','yellow','/admin/lous',$data['payed']));
                $row->column(3, new InfoBox('还款中','clone','blue','/admin/lous',$data['repaying']));
            });

    }

    public function total(){
        return [
         'users'=>WechatUser::query()->count(),
         'creating'=>Lou::query()->where('status',Lou::$statusMap['CREATING'])->count(),
         'payed'=>Lou::query()->where('status',Lou::$statusMap['JIE_LOU_OK'])->count(),
         'repaying'=>Lou::query()->where('status',Lou::$statusMap['JIE_LOU'])->count(),
        ];
    }
}
