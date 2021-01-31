<?php

namespace App\Console\Commands\cron;

use App\Utils\Wechat\RepaymentMessage;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RepaymentNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'z:repayment-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '账单通知';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $notify = new RepaymentMessage();
        $notify->setPage('/pages/index/index');
        $notify->setTemplateId('6agnykuZddRbPjnMSWrZD0iecg32D7kWaMYmD8bOmho');
        $notify->setToUser('oiVhr5XX-NjY0K6WN8CapXHLmJrw');
        $notify->setData(400,'2020-1-12','');
        $data = $notify->getData();
        $app = app('easyWechat');
        $app->subscribe_message->send($data);
        Log::info('repayment_notify:',$data);
    }
}