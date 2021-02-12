<?php

namespace App\Admin\Controllers;

use App\Model\WechatUser;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class WechatUserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '用户';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WechatUser());

        $grid->column('id', 'ID');
        $grid->column('unique_id', '唯一id');
        $grid->column('name', '昵称');
        $grid->column('email', '邮箱');
        $grid->column('credit_score', '信用分');
        $grid->column('openid', 'Openid');
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
        $show = new Show(WechatUser::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('unique_id', '唯一id');
        $show->field('name', '昵称');
        $show->field('email', '邮箱地址');
        $show->field('credit_score', '信用分');
        $show->field('openid', __('Openid'));
        $show->field('created_at', '创建时间');
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
        $form = new Form(new WechatUser());

        $form->text('unique_id', __('Unique id'));
        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->text('credit_score', __('Credit score'))->default('500');
        $form->text('openid', __('Openid'));

        return $form;
    }
}
