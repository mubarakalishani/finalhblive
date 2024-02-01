<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\SubmittedTaskProof;

class SubmittedTaskProofController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SubmittedTaskProof';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SubmittedTaskProof());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('task_id', __('Task id'))->sortable();
        $grid->column('worker_id', __('Worker id'))->sortable();
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('status', __('Status'))->sortable();
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        $grid->model()->orderBy('updated_at', 'desc');

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
        $show = new Show(SubmittedTaskProof::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('task_id', __('Task id'));
        $show->field('worker_id', __('Worker id'));
        $show->field('amount', __('Amount'));
        $show->field('status', __('Status'));
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
        $form = new Form(new SubmittedTaskProof());

        $form->number('task_id', __('Task id'));
        $form->number('worker_id', __('Worker id'));
        $form->decimal('amount', __('Amount'));
        $form->switch('status', __('Status'));

        return $form;
    }
}
