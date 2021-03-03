<?php

namespace App\Listeners;

use App\Utils\Wechat\NotifyMessage;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class MessageNotify implements ShouldQueue
{
    public $queue = 'listener-message';
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle($event)
    {
        /** @var NotifyMessage $notify */
        $notify = $event->tmplMessage;
        $notify->setTemplateId('YtyUhxysvRH-ESC0oiE6CKlKz5tFqS5LtQ801TsTT4k');
        $notify->setData($event->message->title, $event->name,'在小程序【消息通知】中查看', Carbon::now()->format('Y-m-d'));
        $notify->setToUser($event->user->openid);
        $notify->setPage('/pages/index/index');
        $data = $notify->getData();
        $app = app('easyWechat');
        $res = $app->subscribe_message->send($data);
        Log::info($res);
        Log::info('发送消息模板成功',$data);
    }

    public function getContent($content){
        $start = strpos($content,'：');
        return mb_substr($content,$start,20,'utf-8').'...';
    }
}
