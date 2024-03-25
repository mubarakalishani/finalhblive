<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\AdminProofDispute;

class AdminProofDisputeController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AdminProofDispute';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminProofDispute());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('employer_id', __('Employer id'));
        $grid->column('task_id', __('Task id'));
        $grid->column('proof_id', __('Proof id'));
        $grid->column('description', __('Description'));
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
        $show = new Show(AdminProofDispute::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('employer_id', __('Employer id'));
        $show->field('task_id', __('Task id'));
        $show->field('proof_id', __('Proof id'));
        $show->field('description', __('Description'));
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
        $form = new Form(new AdminProofDispute());

        $form->number('user_id', __('User id'));
        $form->number('employer_id', __('Employer id'));
        $form->number('task_id', __('Task id'));
        $form->number('proof_id', __('Proof id'));
        $form->textarea('description', __('Description'));

        return $form;
    }
}
