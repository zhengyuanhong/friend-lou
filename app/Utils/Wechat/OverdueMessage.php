<?php
/**
 *     姓名

{{name1.DATA}}
金额

{{amount2.DATA}}
日期

{{date3.DATA}}
账单类型

{{thing4.DATA}}
备注

{{thing5.DATA}}
 *
 * id = REHzo2YeIq0h9uGan-tRJ9S6VSDCCUAq-CFUQh1h2vQ
 */
namespace App\Utils\Wechat;

class OverdueMessage extends Message{
    public $templateId = '';
    public $toUser = '';
    public $page = '';
    public $data = [
        'name'=>[
            'value'=>''
        ],
        'amount'=>[
            'value'=>''
        ],
        'date'=>[
            'value'=>''
        ],
        'type'=>[
            'value'=>''
        ],
        'note'=>[
            'value'=>''
        ]
    ];

    public function setData($amount,$repayment,$note)
    {
        $this->data['amount']['value'] = $amount;
        $this->data['repayment']['value'] = $repayment;
        $this->data['note']['value'] = $note;
    }
}
