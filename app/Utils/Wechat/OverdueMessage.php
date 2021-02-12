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
    public $templateId = 'REHzo2YeIq0h9uGan-tRJ9S6VSDCCUAq-CFUQh1h2vQ';
    public $touser = '';
    public $page = '';
    public $data = [
        'name1'=>[
            'value'=>''
        ],
        'amount2'=>[
            'value'=>''
        ],
        'date3'=>[
            'value'=>''
        ],
        'thing4'=>[
            'value'=>''
        ],
        'thing5'=>[
            'value'=>''
        ]
    ];

    public function setData($name,$amount,$date,$overdue,$note='欠条')
    {
        $this->data['name1']['value'] = $name;
        $this->data['amount2']['value'] = $amount;
        $this->data['date3']['value'] = $date;
        $this->data['thing4']['value'] = $note;
        $this->data['thing5']['value'] = '逾期'.$overdue.'天';
    }
}
