<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Widgets\Table;
use App\Admin\Actions\Task\Approve;
use App\Admin\Actions\Task\Reject;
use App\Admin\Actions\Task\AdminPause;
use App\Admin\Actions\Task\AdminStop;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Task;
use \App\Models\User;

class TaskController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Task';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Task());

        $grid->column('id', __('Id'));
        $grid->column('employer_id', __('Employer id'))->modal(function ($model) {

            $comments = $model->employer()->get()->map(function ($comment) {
                return $comment->only(['username', 'name', 'created_at']);
            });
        
            return new Table(['Username', 'name', 'release time'], $comments->toArray());
        });
        
        $grid->column('title', __('Title'))->sortable();
        $grid->column('worker_level', __('Worker level'))->sortable();
        $grid->column('category', __('Category'))->sortable();
        $grid->column('sub_category', __('Sub category'))->sortable();
        $grid->column('task_balance', __('Task balance'))->sortable();
        $grid->column('approval_fee', __('Approval fee'));
        $grid->column('rating_time', __('Rating time'))->sortable();
        $grid->column('hold_time', __('Hold time'))->sortable();
        $grid->column('max_budget', __('Max budget'))->sortable();
        $grid->column('daily_budget', __('Daily budget'))->sortable();
        $grid->column('weekly_budget', __('Weekly budget'))->sortable();
        $grid->column('hourly_budget', __('Hourly budget'))->sortable();
        $grid->column('submission_per_hour', __('Submission per hour'))->sortable();
        $grid->column('submission_per_day', __('Submission per day'))->sortable();
        $grid->column('submission_per_week', __('Submission per week'))->sortable();
        $grid->column('status', __('Status'))->display( function($status){
            switch ($status) {
                case 0:
                  return "<span class='badge bg-warning'>Pending Approval</span>";
                  break;
                case 1:
                    return "<span class='badge bg-success'>Approved</span>";
                    break;
                case 2:
                    return "<span class='badge bg-danger'>Rejected</span>";
                    break;
                case 3:
                    return "<span class='badge bg-secondary'>Paused</span>";
                    break;
                case 4:
                    return "<span class='badge bg-success'>Running</span>";
                    break;
                case 5:
                    return "<span class='badge bg-light'>Completed</span>";
                    break;
                case 6:
                    return "<span class='badge bg-info'>Budget Exceeded</span>";
                    break; 
                case 7:
                    return "<span class='badge bg-primary'>paused Admin</span>";
                    break;
                case 8:
                    return "<span class='badge bg-dark'>Admin Stopped</span>";
                    break;             
                default:
                return "<span class='badge bg-primary'>$status</span>";
              }
        })->sortable();


        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'));

        $grid->quickSearch(function ($model, $query) {
            $model->where('employer_id', 'like', "%{$query}%");
        });

        $grid->filter(function($filter){
            $filter->like('employer_id', 'employer id');
            $filter->in('status')->multipleSelect(['0' => 'Pending Approval', '1' => 'approved' , '2' => 'rejected', '4' => 'running', '5' => 'completed', '6' => 'budget exceeded', '7' => 'Paused Admin', '8' => 'Admin Stopped' ]);
            $filter->between('created_at', 'Created Between')->datetime();
            $filter->where(function ($query) {

                $query->whereHas('employer', function ($query) {
                    $query->where('username', 'like', "%{$this->input}%");
                });
            
            }, 'Employer Username');
        });
       
        

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\Task\AdminPause());
            $tools->append(new \App\Admin\Actions\Task\AdminStop());
            $tools->append(new \App\Admin\Actions\Task\Approve());
            $tools->append(new \App\Admin\Actions\Task\Reject());
        });

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
        $show = new Show(Task::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('employer_id', __('Employer id'));
        $show->field('title', __('Title'));
        $show->field('worker_level', __('Worker level'));
        $show->field('category', __('Category'));
        $show->field('sub_category', __('Sub category'));
        $show->field('task_balance', __('Task balance'));
        $show->field('approval_fee', __('Approval fee'));
        $show->field('rating_time', __('Rating time'));
        $show->field('hold_time', __('Hold time'));
        $show->field('max_budget', __('Max budget'));
        $show->field('daily_budget', __('Daily budget'));
        $show->field('weekly_budget', __('Weekly budget'));
        $show->field('hourly_budget', __('Hourly budget'));
        $show->field('submission_per_hour', __('Submission per hour'));
        $show->field('submission_per_day', __('Submission per day'));
        $show->field('submission_per_week', __('Submission per week'));
        $show->field('status', __('Status'));
        $show->field('created_at', __('Created at'));
        $show->field('updated_at', __('Updated at'));

        $show->stepDetails('stepDetails', function ($stepDetails) {
            // If you need to set the resource URL for steps
            $stepDetails->setResource('/admin/task-steps');
        
            $stepDetails->step_no();
            $stepDetails->step_details();
            
            // You can include other step fields as needed
        });
        $show->requiredProofs('requiredProofs', function ($requiredProofs) {
            // If you need to set the resource URL for steps
            $requiredProofs->setResource('/admin/task-required-proofs');
        
            $requiredProofs->id();
            $requiredProofs->proof_type()->display( function($proof_type){
                switch ($proof_type) {
                    case 1:
                      return "<span class='badge bg-secondary'>Text</span>";
                      break;
                    case 2:
                        return "<span class='badge bg-primary'>Screenshot</span>";
                      break;
                    default:
                    return "<span class='badge bg-dark'>$proof_type</span>";
                  }
            });;
            $requiredProofs->proof_text();
            
            // You can include other step fields as needed
        });

        $show->declineReason('declineReason', function ($declineReason) {
            // If you need to set the resource URL for steps
            $declineReason->setResource('/admin/admin-task-decline-reasons');
        
            $declineReason->id();
            $declineReason->reason();
            
            // You can include other step fields as needed
        });

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new Task());

        $form->text('employer_id', __('Employer id'));
        $form->text('title', __('Title'));
        $form->text('worker_level', __('Worker level'))->default('starter');
        $form->number('category', __('Category'));
        $form->number('sub_category', __('Sub category'));
        $form->decimal('task_balance', __('Task balance'))->default(0.00);
        $form->decimal('approval_fee', __('Approval fee'))->default(0.0000);
        $form->number('rating_time', __('Rating time'));
        $form->number('hold_time', __('Hold time'));
        $form->decimal('max_budget', __('Max budget'))->default(0.00);
        $form->decimal('daily_budget', __('Daily budget'))->default(0.00);
        $form->decimal('weekly_budget', __('Weekly budget'))->default(0.00);
        $form->decimal('hourly_budget', __('Hourly budget'))->default(0.00);
        $form->number('submission_per_hour', __('Submission per hour'));
        $form->number('submission_per_day', __('Submission per day'));
        $form->number('submission_per_week', __('Submission per week'));
        $form->number('status', __('Status'));

        return $form;
    }
}
