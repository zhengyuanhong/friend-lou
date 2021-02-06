<?php

namespace App\Utils\Wechat;

class RepaymentMessage extends Message
{
    public $template_id = '';
    public $touser = '';
    public $page = '';
    public $data = [
        'amount23'=>[
            'value'=>''
        ],
        'time22'=>[
            'value'=>''
        ],
        'thing9'=>[
            'value'=>''
        ]
    ];

    public function setData($amount,$repayment,$note)
    {
        $this->data['amount23']['value'] = $amount;
        $this->data['time22']['value'] = $repayment;
        $this->data['thing9']['value'] = $note;
    }
}