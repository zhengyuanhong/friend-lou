<?php
namespace App\Utils\Wechat;

class Message {
    public $template_id = '';
    public $touser = '';
    public $page = '';
    public $data = [];

    public function setTemplateId($template_id){
        $this->template_id = $template_id;
    }

    public function setToUser($touser){
       $this->touser = $touser;
    }

    public function setPage($page){
        $this->page = $page;
    }

    public function getData(){
        return get_object_vars($this);
    }
}