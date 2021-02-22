<?php

namespace App\Admin\Controllers;

use App\Model\Message;
use App\Model\WechatUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class MessageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '消息';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Message());

        $grid->column('id', 'ID');
        $grid->column('user_id', '接收者')->display(function ($id) {
            return WechatUser::query()->find($id)->name ?: '';
        });
        $grid->column('lou_id', '欠条id');
        $grid->column('type', '类型')->display(function ($type) {
            $typeMap = [
                'lou' => '<text style="color: greenyellow;">已还款提醒</text>',
                'bind' => '<text style="color: blueviolet;">绑定消息</text>',
                'msg' => '<text style="color: orange;">系统消息</text>',
                'repayment' => '<text style="color: #0e1480;">还款提醒</text>',
                'overdue' => '<text style="color: red;">逾期提醒</text>'
            ];
            return $typeMap[$type];
        });
        $grid->column('title', '标题');
        $grid->column('content', '内容');
        $grid->column('deleted_at', '删除时间');
        $grid->column('is_read', '状态')->display(function ($status) {
            $statusMap = [
                0 => '<text style="color:red;">未读</textc>',
                1 => '<text style="color:#0e1480;">已读</text>',
                2 => '<text style="color:#0e1480;">借条邀请已接受</text>',
                3 => '<text style="color:red;">借条邀请已拒绝</text>',
            ];

            return $statusMap[$status];
        });
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param mixed $id
     * @return Show
     */
    protected function detail($id)
    {
        $show = new Show(Message::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('user_id', '用户id');
        $show->field('lou_id', '借条id');
        $show->field('type', '类型');
        $show->field('title', '标题');
        $show->field('content', '内容');
        $show->field('deleted_at', '删除时间');
        $show->field('is_read', '状态');
        $show->field('created_at', '创建时间 ');
        $show->field('updated_at', '更新时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Message());

        $form->number('user_id', '用户id');
        $form->number('lou_id', '欠条id');
        $form->text('type', '类型');
        $form->text('title', '标题');
        $form->text('content', '内容');
        $form->switch('is_read', '状态');

        return $form;
    }
}
