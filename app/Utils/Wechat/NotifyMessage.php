<?php

namespace App\Utils\Wechat;

class NotifyMessage extends Message
{
    public $template_id = 'YtyUhxysvRH-ESC0oiE6CKlKz5tFqS5LtQ801TsTT4k';
    public $touser = '';
    public $page = '';
    public $data = [
        'thing1' => [
            'value' => ''
        ],
        'name2' => [
            'value' => ''
        ],
        'thing3' => [
            'value' => ''
        ],
        'time4' => [
            'value' => ''
        ]
    ];

    public function setData($topic, $name, $content, $date)
    {
        $this->data['thing1']['value'] = $topic;
        $this->data['name2']['value'] = $name;
        $this->data['thing3']['value'] = $content;
        $this->data['time4']['value'] = $date;
    }
}
