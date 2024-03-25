<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\TaskTargetedCountry;

class TaskTargetedCountriesController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'TaskTargetedCountry';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TaskTargetedCountry());

        $grid->column('id', __('Id'));
        $grid->column('task_id', __('Task id'));
        $grid->column('country', __('Country'));
        $grid->column('amount_per_task', __('Amount per task'));
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
        $show = new Show(TaskTargetedCountry::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('task_id', __('Task id'));
        $show->field('country', __('Country'));
        $show->field('amount_per_task', __('Amount per task'));
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
        $form = new Form(new TaskTargetedCountry());

        $form->number('task_id', __('Task id'));
        $form->text('country', __('Country'));
        $form->decimal('amount_per_task', __('Amount per task'))->default(0.000);

        return $form;
    }
}
