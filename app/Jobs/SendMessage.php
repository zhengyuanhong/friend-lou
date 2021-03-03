<?php

namespace App\Jobs;

use App\Utils\Wechat\NotifyMessage;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $message;
    public $user;
    public $tmplMessage;
    public $name;

    /**
     * Create a new event instance.
     * @param  $message
     * @param $user
     * @param $name
     * @param $tmplMessage
     * @return void
     */
    public function __construct($tmplMessage, $user,$message,$name)
    {
        $this->message = $message;
        $this->tmplMessage = $tmplMessage;
        $this->user = $user;
        $this->name = $name;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        /** @var NotifyMessage $notify */
        $notify = $this->tmplMessage;
        $notify->setTemplateId('YtyUhxysvRH-ESC0oiE6CKlKz5tFqS5LtQ801TsTT4k');
        $notify->setData($this->message->title, $this->name,'在小程序【消息通知】中查看', Carbon::now()->format('Y-m-d'));
        $notify->setToUser($this->user->openid);
        $notify->setPage('/pages/index/index');
        $data = $notify->getData();
        $app = app('easyWechat');
        $res = $app->subscribe_message->send($data);
        Log::info($res);
        Log::info('发送消息模板成功',$data);
    }
}
