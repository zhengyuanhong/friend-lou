<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BannerResource;
use App\Model\Banner;
use App\Model\Config;
use App\Model\Lou;
use App\Model\Message;
use App\Utils\ErrorCode;
use Illuminate\Http\Request;

class IndexController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user;
        $bill = [];
        $bill['jie'] = $user->louJIe()->where('status', Lou::$statusMap['JIE_LOU'])->sum('amount');
        $bill['qian'] = $user->louQian()->where('status', Lou::$statusMap['QIAN_LOU'])->sum('amount');

        $lou_count = [];
        $lou_count['creating'] = Lou::query()->where('creator', $user->id)
            ->where('status', Lou::$statusMap['CREATING'])->count();
        $lou_count['lou_qian'] = Lou::query()->where('debts_user_id', $user->id)
            ->where('status', Lou::$statusMap['QIAN_LOU'])->count();


        $data['bill'] = $bill;
        $data['lou_count'] = $lou_count;
        $data['msg_count'] = Message::query()->where('is_read', 0)->where('user_id', $user->id)->count();

        $data['config'] = Config::getIndexConifg();

        $data['banner'] = BannerResource::collection(Banner::query()->where('is_show', 1)->orderBy('weight','DESC')->limit(10)->get());

        return $this->response_json(ErrorCode::SUCCESS, $data);
    }
}
