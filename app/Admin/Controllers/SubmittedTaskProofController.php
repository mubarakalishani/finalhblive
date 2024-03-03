<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\SubmittedTaskProof;
use App\Models\User;

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
        $grid->column('worker_id', __('Worker'))->display( function($userid){
            $username = User::where('id', $userid)->value('username');
            return "<span>$username</span>";
        })->sortable();
        $grid->column('amount', __('Amount'))->sortable();
        $grid->column('status', __('Status'))->display( function($status){
            switch ($status) {
                case 0:
                  return "<span class='badge bg-warning'>pending</span>";
                  break;
                case 1:
                    return "<span class='badge bg-success'>Approved</span>";
                    break;
                case 2:
                    return "<span class='badge bg-danger'>Rejected</span>";
                    break;
                case 3:
                    return "<span class='badge bg-secondary'>asked_resubmit</span>";
                    break;
                case 4:
                    return "<span class='badge bg-success'>resubmitted</span>";
                    break;
                case 5:
                    return "<span class='badge bg-light'>dispute filed</span>";
                    break;
                case 6:
                    return "<span class='badge bg-info'>dispute rejected</span>";
                    break; 
                case 7:
                    return "<span class='badge bg-primary'>resubmission exhausted</span>";
                    break;            
                default:
                return "<span class='badge bg-primary'>$status</span>";
              }
        })->sortable();
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        $grid->model()->orderBy('updated_at', 'desc');

        $grid->filter(function($filter){
            $filter->equal('worker_id', 'User Id');
            $filter->equal('task_id', 'Task Id');
            $filter->between('created_at', 'submitted between')->datetime();
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
