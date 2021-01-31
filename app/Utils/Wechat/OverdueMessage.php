<?php
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
