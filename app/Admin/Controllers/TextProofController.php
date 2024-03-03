<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\TextProof;

class TextProofController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'TextProof';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TextProof());

        $grid->column('id', __('Id'));
        $grid->column('submitted_proof_id', __('Submitted proof id'));
        $grid->column('task_id', __('Task id'));
        $grid->column('proof_no', __('Proof no'));
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
        $show = new Show(TextProof::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('submitted_proof_id', __('Submitted proof id'));
        $show->field('task_id', __('Task id'));
        $show->field('proof_no', __('Proof no'));
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
        $form = new Form(new TextProof());

        $form->number('submitted_proof_id', __('Submitted proof id'));
        $form->number('task_id', __('Task id'));
        $form->number('proof_no', __('Proof no'));
        $form->text('proof_text', __('Proof text'));

        return $form;
    }
}
