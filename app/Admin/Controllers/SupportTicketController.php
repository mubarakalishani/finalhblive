<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\SupportTicket;

class SupportTicketController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'SupportTicket';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new SupportTicket());

        $grid->column('id', __('Id'));
        $grid->column('user_id', __('User id'));
        $grid->column('department_id', __('Department id'));
        $grid->column('name', __('Name'));
        $grid->column('email', __('Email'));
        $grid->column('subject', __('Subject'));
        $grid->column('message', __('Message'));
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
        $show = new Show(SupportTicket::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('department_id', __('Department id'));
        $show->field('name', __('Name'));
        $show->field('email', __('Email'));
        $show->field('subject', __('Subject'));
        $show->field('message', __('Message'));
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
        $form = new Form(new SupportTicket());

        $form->number('user_id', __('User id'));
        $form->number('department_id', __('Department id'));
        $form->text('name', __('Name'));
        $form->email('email', __('Email'));
        $form->textarea('subject', __('Subject'));
        $form->textarea('message', __('Message'));

        return $form;
    }
}
