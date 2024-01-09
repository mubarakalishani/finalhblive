<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use App\Models\TaskRequiredProof;

class TaskRequiredProofs extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'TaskRequiredProof';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TaskRequiredProof());

        $grid->column('id', __('Id'));
        $grid->column('task_id', __('Task id'));
        $grid->column('proof_type', __('Proof type'));
        $grid->column('proof_text', __('Proof text'));
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
        $show = new Show(TaskRequiredProof::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('task_id', __('Task id'));
        $show->field('proof_type', __('Proof type'));
        $show->field('proof_text', __('Proof text'));
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
        $form = new Form(new TaskRequiredProof());

        $form->number('task_id', __('Task id'));
        $form->switch('proof_type', __('Proof type'));
        $form->text('proof_text', __('Proof text'));

        return $form;
    }
}
