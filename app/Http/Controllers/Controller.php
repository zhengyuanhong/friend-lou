<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;


    public function response_json($error_code, $data = [])
    {
        $arr = [
            'message' => $error_code['message'],
            'code' => $error_code['code'],
            'data' => $data,
            'time' => time(),
        ];
        return response()->json($arr);
    }
}
