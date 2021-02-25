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

class sendMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $notify = new NotifyMessage();
        $notify->setPage('/pages/index/index');
        //留言通知
        $notify->setTemplateId('YtyUhxysvRH-ESC0oiE6CKlKz5tFqS5LtQ801TsTT4k');
        //账单通知
//        $notify->setTemplateId('6agnykuZddRbPjnMSWrZD0iecg32D7kWaMYmD8bOmho');
//        $notify->setTemplateId('6agnykuZddRbPjnMSWrZD0iecg32D7kWaMYmD8bOmho');
        $notify->setToUser('oiVhr5XX-NjY0K6WN8CapXHLmJrw');
        $notify->setData(400,'2020-1-12','test','ddd');
        $data = $notify->getData();
        $app = app('easyWechat');
        $app->subscribe_message->send($data);
        Log::info('repayment_notify:',$data);
    }
}
