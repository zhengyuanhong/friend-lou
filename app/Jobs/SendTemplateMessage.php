<?php

namespace App\Jobs;

use App\Model\Lou;
use App\Model\WechatUser;
use App\Services\MsgService;
use App\Utils\Wechat\OverdueMessage;
use App\Utils\Wechat\RepaymentMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendTemplateMessage implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $user;
    public $lou;
    public $overdue;
    public $type;

    /**
     * Create a new job instance.
     * @param WechatUser $user ;
     * @param Lou $lou ;
     * @param $overdue ;
     * @param $type ;
     * @return void
     */
    public function __construct(WechatUser $user, Lou $lou, $overdue = 0, $type = 'overdue')
    {
        $this->user = $user;
        $this->lou = $lou;
        $this->overdue = $overdue;
        $this->type = $type;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        switch ($this->type) {
            case 'overdue':
                $this->sendOverdueMsg($this->user, $this->lou, $this->overdue);
                break;
            case 'repayment':
                $this->sendRepaymentMessage($this->user, $this->lou);
                break;
        }
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
        Log::info('queue:repayment_notify:', $data);
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
        Log::info('queue:repayment_notify:', $data);
    }

    public function sendMsg($lou, $type, $overdue = 0)
    {
        $msg = new MsgService();
        $msg->createTemplateMsg($lou, $type, $overdue);
    }
}
