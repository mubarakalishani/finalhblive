<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\AdminTaskDeclineReason;

class TaskDeclineReasonController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AdminTaskDeclineReason';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AdminTaskDeclineReason());

        $grid->column('id', __('Id'));
        $grid->column('task_id', __('Task id'));
        $grid->column('reason', __('Reason'));
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->number('task_id', 'task_id')->placeholder('Task Id');
            $create->text('reason', 'reason')->placeholder('Decline Reason');
        });

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
        $show = new Show(AdminTaskDeclineReason::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('task_id', __('Task id'));
        $show->field('reason', __('Reason'));
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
        $form = new Form(new AdminTaskDeclineReason());

        $form->number('task_id', __('Task id'));
        $form->text('reason', __('Reason'));

        return $form;
    }
}
