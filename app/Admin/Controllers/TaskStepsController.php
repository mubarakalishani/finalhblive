<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\TaskStep;

class TaskStepsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'TaskStep';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TaskStep());

        $grid->column('id', __('Id'));
        $grid->column('task_id', __('Task id'));
        $grid->column('step_no', __('Step no'));
        $grid->column('step_details', __('Step details'))->text();

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
        $show = new Show(TaskStep::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('task_id', __('Task id'));
        $show->field('step_no', __('Step no'));
        $show->field('step_details', __('Step details'));
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
        $form = new Form(new TaskStep());

        $form->number('task_id', __('Task id'));
        $form->number('step_no', __('Step no'));
        $form->textarea('step_details', __('Step details'))->default('text');

        return $form;
    }
}
