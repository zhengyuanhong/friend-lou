<?php

namespace App\Utils\Wechat;

class RepaymentMessage extends Message
{
    public $template_id = '';
    public $touser = '';
    public $page = '';
    public $data = [
        'amount'=>[
            'value'=>''
        ],
        'repayment'=>[
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