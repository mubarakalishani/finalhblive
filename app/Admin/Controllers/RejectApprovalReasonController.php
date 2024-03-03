<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\RejectApprovalReason;

class RejectApprovalReasonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'RejectApprovalReason';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new RejectApprovalReason());

        $grid->column('id', __('Id'));
        $grid->column('submitted_proof_id', __('Submitted proof id'));
        $grid->column('selected_reason', __('Selected reason'));
        $grid->column('employer_comment', __('Employer comment'));
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
        $show = new Show(RejectApprovalReason::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('submitted_proof_id', __('Submitted proof id'));
        $show->field('selected_reason', __('Selected reason'));
        $show->field('employer_comment', __('Employer comment'));
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
        $form = new Form(new RejectApprovalReason());

        $form->number('submitted_proof_id', __('Submitted proof id'));
        $form->text('selected_reason', __('Selected reason'));
        $form->text('employer_comment', __('Employer comment'));

        return $form;
    }
}
