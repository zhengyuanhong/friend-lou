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

    protected $states = [
        'on' => ['value' => 1, 'text' => '显示', 'color' => 'primary'],
        'off' => ['value' => 0, 'text' => '隐藏', 'color' => 'default'],
    ];

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

        $grid->column('is_show', '是否显示')->switch($this->states);
        $grid->column('weight','显示权重')->editable();
        $grid->column('miniappid', '小程序appid');
        $grid->column('url', '图片链接')->display(function ($url) {
            return '<img style="width:320px;height:100px;" src=' . $url . ' />';
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
        $show = new Show(Banner::findOrFail($id));

        $show->field('id', 'ID');
        $show->field('type', '图片类型');
        $show->field('weight','显示权重');
        $show->field('is_show', '是否显示');
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

        $form->select('type', '图片类型')->options(['miniapp' => '小程序图片', 'image' => '纯图片']);
        $form->switch('is_show', '是否显示')->states($this->states);
        $form->number('weight','显示权重')->max(100);
        $form->text('miniappid', '小程序appid');
//        $form->url('url', '图片链接');
        $form->image('url', '图片上传');
        return $form;
    }
}
