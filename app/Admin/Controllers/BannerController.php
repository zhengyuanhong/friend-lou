<?php

namespace App\Admin\Controllers;

use App\Model\Banner;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Show;

class BannerController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = '首页轮播图';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Banner());

        $grid->column('id', 'ID');
        $grid->column('type', '图片类型');
        $grid->column('miniappid', '小程序appid');
        $grid->column('url', __('图片链接'));
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
        $show = new Show(Banner::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('type', '图片类型');
        $show->field('miniappid', '小程序appid');
        $show->field('url', '图片链接');
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
        $form = new Form(new Banner());

        $form->select('type', '图片类型')->options(['miniapp'=>'小程序图片','image'=>'纯图片']);
        $form->text('miniappid', '小程序appid');
        $form->url('url', '图片链接');
        return $form;
    }
}
