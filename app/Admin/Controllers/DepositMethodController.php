<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\DepositMethod;

class DepositMethodController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'DepositMethod';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new DepositMethod());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('min_deposit', __('Min deposit'));
        $grid->column('status', __('Status'));
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
        $show = new Show(DepositMethod::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('min_deposit', __('Min deposit'));
        $show->field('status', __('Status'));
        $show->field('auto', __('Auto Gateway?'));
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
        $form = new Form(new DepositMethod());

        $form->text('name', __('Name'));
        $form->decimal('min_deposit', __('Min deposit'))->default(0.00);
        $form->switch('status', __('Status'))->default(1);
        $form->switch('auto', __('Automatic Gateway?'))->default(1);
        $form->ckeditor('description');

        return $form;
    }
}
