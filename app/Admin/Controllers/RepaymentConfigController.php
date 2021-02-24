<?php

namespace App\Admin\Controllers;

use App\Model\RepaymentTimeConfig;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class RepaymentConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '还款时间设置';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RepaymentTimeConfig());

        $grid->column('id', 'id');
        $grid->column('day', '还款期限');
        $grid->column('description', '描述');
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
        $show = new Show(RepaymentTimeConfig::findOrFail($id));

        $show->field('id', 'Id');
        $show->field('day', '还款期限');
        $show->field('description', '描述');
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
        $form = new Form(new RepaymentTimeConfig());

        $form->number('day', __('还款期限'));
        $form->text('description', __('描述'));

        return $form;
    }
}
