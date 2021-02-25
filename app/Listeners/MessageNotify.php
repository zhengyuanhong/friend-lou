<?php

namespace App\Listeners;

use App\Utils\Wechat\NotifyMessage;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class MessageNotify
{
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
        $notify->setData($event->message->title, $event->name, $event->message->content, Carbon::now());
        $notify->setToUser($event->user->openid);
        $notify->setPage('/pages/index/index');
        $data = $notify->getData();
        $app = app('easyWechat');
        $app->subscribe_message->send($data);
        Log::info('发送消息模板成功',$data);
    }
}
