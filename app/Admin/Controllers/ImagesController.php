<?php

namespace App\Admin\Controllers;

use App\Model\Images;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class ImagesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '图片资源';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Images());

        $grid->column('id', 'Id');
        $grid->column('path', '路径')->display(function($path){
            return '<img style="width:100px;height:100px;" src=' . $path . ' />';
        });
        $grid->column('name', '名称');
        $grid->column('updated_at', __('Updated at'));
        $grid->column('created_at', __('Created at'));

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
        $show = new Show(Images::findOrFail($id));

        $show->field('id', 'Id');
        $show->field('path', '路径');
        $show->field('name', '名称');
        $show->field('updated_at', __('Updated at'));
        $show->field('created_at', __('Created at'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Images());

        $form->text('name', '名称');
        $form->image('path', '路径');

        return $form;
    }
}
