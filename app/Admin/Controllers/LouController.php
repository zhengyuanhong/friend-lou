<?php

namespace App\Admin\Controllers;

use App\Model\Lou;
use App\Model\WechatUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class LouController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '欠条';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lou());

        $grid->column('id', 'ID');
        $grid->column('creditors_user_id', '债权人')->display(function ($id) {
                return empty($id) ? '暂无' : WechatUser::query()->find($id)->name;
        });
        $grid->column('debts_user_id', '债务人')->display(function ($id) {
            return empty($id) ? '暂无' : WechatUser::query()->find($id)->name;
        });
        $grid->column('amount', '金额');
        $grid->column('note', '备注');
        $grid->column('creator', '创建人')->display(function ($id) {
            return WechatUser::query()->find($id)->name ?: '暂无';
        });
        $grid->column('status', '状态')->display(function ($status) {
            $statusMap = [
                0 => '<text style="color: blue;">正在创建</text>',
                1 => '<text style="color: orange;">借还中</text>',
                2 => '<text style="color: green;">已还清</text>'
            ];
            return $statusMap[$status];
        });
        $grid->column('repayment_at', '还款日期')->display(function ($repayment_at) {
            $res = Lou::diffTime($repayment_at);
            if ($res == 'overdue') {
                return '<text style="color: red;">已逾期</text>';
            }
            return "<text style='color: orange;'>离还款还有{$res}天</text>";
        });
        $grid->column('duration', '还款期限')->display(function ($day) {
            return "<text>{$day}天</text>";
        });
        $grid->column('created_at', '创建时间');
        $grid->column('updated_at', '更新时间');
        $grid->column('deleted_at', '删除时间');

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
        $show = new Show(Lou::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('creditors_user_id', "债权人");
        $show->field('debts_user_id', '债务人');
        $show->field('amount', '金额');
        $show->field('note', '备注');
        $show->field('creator', '创建人');
        $show->field('status', '状态');
        $show->field('repayment_at', '还款日期');
        $show->field('duration', '还款期限');
        $show->field('created_at', '创建时间');
        $show->field('updated_at', '更新时间');
        $show->field('deleted_at', '删除时间');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Lou());

        $form->number('creditors_user_id', '债权人');
        $form->number('debts_user_id', '债务人');
        $form->decimal('amount', '金额');
        $form->text('note', '备注');
        $form->number('creator', '备注');
        $form->switch('status', '状态');
        $form->datetime('repayment_at', '还款日期')->default(date('Y-m-d H:i:s'));
        $form->text('duration', '还款期限');

        return $form;
    }
}
