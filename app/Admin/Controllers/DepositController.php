<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Deposit;
use App\Models\DepositMethod;
use App\Models\User;

class DepositController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Deposit';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Deposit());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('user_id', __('Username'))->display( function($userid){
            $username = User::where('id', $userid)->value('username');
            return "<span>$username</span>";
        })->sortable();
        $grid->column('method', __('Method'))->sortable();
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('status', __('Status'))->sortable();
        $grid->column('internal_tx', __('Internal tx'));
        $grid->column('external_tx', __('External tx'));
        $grid->column('description', __('Description'));
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->filter(function($filter){
            // Add a column filter
            $depositMethods = DepositMethod::pluck('name', 'name')->toArray();
            $filter->equal('user_id', 'User ID');
            $filter->where(function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('username', 'like', "%{$this->input}%");
                });
            }, 'Username');
            $filter->in('method', 'Deposit Method')->multipleSelect($depositMethods);
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
        $show = new Show(Deposit::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('method', __('Method'));
        $show->field('amount', __('Amount'));
        $show->field('status', __('Status'));
        $show->field('internal_tx', __('Internal tx'));
        $show->field('external_tx', __('External tx'));
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
        $form = new Form(new Deposit());

        $form->number('user_id', __('User id'));
        $form->text('method', __('Method'));
        $form->decimal('amount', __('Amount'))->default(0.00);
        $form->text('status', __('Status'));
        $form->text('internal_tx', __('Internal tx'))->value(bin2hex(random_bytes(6)));
        $form->textarea('external_tx', __('External tx'));
        $form->textarea('description', __('Description'));

        return $form;
    }
}
