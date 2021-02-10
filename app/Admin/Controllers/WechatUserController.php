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
    protected $title = 'WechatUser';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WechatUser());

        $grid->column('id', __('Id'));
        $grid->column('unique_id', __('Unique id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('credit_score', __('Credit score'));
        $grid->column('openid', __('Openid'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

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

        $show->field('id', __('Id'));
        $show->field('unique_id', __('Unique id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('credit_score', __('Credit score'));
        $show->field('openid', __('Openid'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

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