<?php

namespace App\Admin\Controllers;

use App\Model\Config;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ConfigController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Config';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Config());

        $grid->column('id', '序号');
        $grid->column('type', '类型');
        $grid->column('key', '关键字');
        $grid->column('value', '内容')->editable();
        $grid->column('description', '描述');

        $grid->disableActions();
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
        $show = new Show(Config::findOrFail($id));

//        $show->field('id', '序号');
        $show->field('type', '类型');
        $show->field('key', '关键字');
        $show->field('value', '内容');
        $show->field('description', '描述');

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Config());

//        $form->text('type', '类型');
        $form->text('key', '关键字');
        $form->text('value', '内容');
        $form->text('description', '描述');

        return $form;
    }
}
