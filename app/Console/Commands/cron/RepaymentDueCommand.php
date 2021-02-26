<?php

namespace App\Console\Commands\cron;

use App\Jobs\SendTemplateMessage;
use App\Model\Lou;
use App\Model\Message;
use App\Model\WechatUser;
use App\Services\MsgService;
use App\Utils\Wechat\OverdueMessage;
use App\Utils\Wechat\RepaymentMessage;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RepaymentDueCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'z:repayment-due';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '到期还款提醒';

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
        Lou::query()
            ->where('status', Lou::$statusMap['JIE_LOU'])
            ->with('louQianBeLongsToUser')
            ->chunkById(1000, function ($items) {
                foreach ($items as $item) {
                    $dua = Carbon::now()->diffInDays($item->repayment_at);
                    if (Carbon::now()->gt(Carbon::parse($item->repayment_at)) && $dua > 0) {
                        Log::info('逾期' . $dua . '天' . $item);
//                        $this->sendOverdueMsg($item->louQianBelongsToUser, $item, $dua);
                        SendTemplateMessage::dispatch($item->louQianBelongsToUser, $item, $dua, 'overdue');
                    } else if ($dua >= 0 && $dua <= 1) {
                        //订阅消息提醒
                        Log::info('还差' . $dua . '到期，订阅消息提醒');
//                        $this->sendRepaymentMessage($item->louQianBelongsToUser, $item);
                        SendTemplateMessage::dispatch($item->louQianBelongsToUser, $item, $dua, 'repayment');
                    }
                }
            });
        Log::info('查询是否到还款时间');
        return;
    }

    public function sendOverdueMsg(WechatUser $user, Lou $lou, $overdue = 0)
    {
        if ($lou->overdueTemplateMessage()->exists()) {
            Log::info('逾期消息模板已发送');
            return;
        }
        $notify = new OverdueMessage();
        $notify->setTemplateId('REHzo2YeIq0h9uGan-tRJ9S6VSDCCUAq-CFUQh1h2vQ');
        $notify->setData($user->name, $lou->amount, $lou->repayment_at, $overdue);
        $notify->setToUser($user->openid);
        $notify->setPage('/pages/index/index');
        $data = $notify->getData();
        $app = app('easyWechat');
        $app->subscribe_message->send($data);

        $this->sendMsg($lou, 'overdue', $overdue);
        Log::info('repayment_notify:', $data);
    }

    public function sendRepaymentMessage(WechatUser $user, Lou $lou)
    {
        if ($lou->repaymentTemplateMessage()->exists()) {
            Log::info('账单消息模板已发送');
            return;
        }
        $notify = new RepaymentMessage();
        $notify->setTemplateId('6agnykuZddRbPjnMSWrZD0iecg32D7kWaMYmD8bOmho');
        $notify->setData($lou->amount, $lou->repayment_at, $lou->note ?: '暂无');
        $notify->setToUser($user->openid);
        $notify->setPage('/pages/index/index');
        $data = $notify->getData();
        $app = app('easyWechat');
        $app->subscribe_message->send($data);

        $this->sendMsg($lou, 'repayment');
        Log::info('repayment_notify:', $data);
    }

    public function sendEmail()
    {

    }

    public function sendMsg($lou, $type, $overdue = 0)
    {
        $msg = new MsgService();
        $msg->createTemplateMsg($lou, $type, $overdue);
    }
}
