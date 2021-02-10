<?php

namespace App\Admin\Controllers;

use App\Model\Lou;
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
    protected $title = 'Lou';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Lou());

        $grid->column('id', __('Id'));
        $grid->column('creditors_user_id', __('Creditors user id'));
        $grid->column('debts_user_id', __('Debts user id'));
        $grid->column('amount', __('Amount'));
        $grid->column('note', __('Note'));
        $grid->column('creator', __('Creator'));
        $grid->column('status', __('Status'));
        $grid->column('repayment_at', __('Repayment at'));
        $grid->column('duration', __('Duration'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));
        $grid->column('deleted_at', __('Deleted at'));

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

        $show->field('id', __('Id'));
        $show->field('creditors_user_id', __('Creditors user id'));
        $show->field('debts_user_id', __('Debts user id'));
        $show->field('amount', __('Amount'));
        $show->field('note', __('Note'));
        $show->field('creator', __('Creator'));
        $show->field('status', __('Status'));
        $show->field('repayment_at', __('Repayment at'));
        $show->field('duration', __('Duration'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));
        $show->field('deleted_at', __('Deleted at'));

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

        $form->number('creditors_user_id', __('Creditors user id'));
        $form->number('debts_user_id', __('Debts user id'));
        $form->decimal('amount', __('Amount'));
        $form->text('note', __('Note'));
        $form->number('creator', __('Creator'));
        $form->switch('status', __('Status'));
        $form->datetime('repayment_at', __('Repayment at'))->default(date('Y-m-d H:i:s'));
        $form->text('duration', __('Duration'));

        return $form;
    }
}
