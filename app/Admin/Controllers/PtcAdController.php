<?php

namespace App\Admin\Controllers;

use App\Models\DepositMethod;
use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PtcAd;
use App\Models\User;

class PtcAdController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PtcAd';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PtcAd());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('status', __('Status'))->display(function ($status) {
            switch ($status) {
                case 0:
                  return "<span class='badge bg-warning'>Pending Approval</span>";
                  break;
                case 1:
                    return "<span class='badge bg-success'>approved/running</span>";
                  break;
                case 2:
                    return "<span class='badge bg-danger'>rejected</span>";
                  break;
                case 3:
                    return "<span class='badge bg-secondary'>paused</span>";
                  break;
                case 4:
                    return "<span class='badge bg-dark'>completed</span>";
                  break;
                case 5:
                    return "<span class='badge bg-dark'>Admin Paused</span>";
                  break;
                default:
                return "<span class='badge bg-primary'>$status</span>";
              }
        
        })->sortable();
        $grid->column('title', __('Title'));
        $grid->column('description', __('Description'));
        $grid->column('url', __('Url'));
        $grid->column('employer_id', __('Employer'))->display( function($userid){
          $username = User::where('id', $userid)->value('username');
          return "<span>$username</span>";
      })->sortable();
        $grid->column('ad_balance', __('Ad balance'))->sortable();
        $grid->column('temp_locked_balance', __('Temp locked balance'))->sortable();
        $grid->column('reward_per_view', __('Reward per view'))->sortable();
        $grid->column('views_needed', __('Views needed'))->sortable();
        $grid->column('views_completed', __('Views completed'))->sortable();
        $grid->column('seconds', __('Seconds'))->sortable();
        $grid->column('revision_interval', __('Revision interval'))->sortable();
        $grid->column('type', __('Type'))->sortable()->display(function ($type) {
            switch ($type) {
                case 0:
                  return "<span class='badge bg-secondary'>Iframe</span>";
                  break;
                case 1:
                    return "<span class='badge bg-info'>Windows</span>";
                  break;
                default:
                return "<span class='badge bg-primary'>$type</span>";
              }
        
        })->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->filter(function($filter){
          $filter->equal('employer_id', 'employer ID');
          $filter->where(function ($query) {
              $query->whereHas('employer', function ($query) {
                  $query->where('username', 'like', "%{$this->input}%");
              });
          }, 'Employer Username');
          $filter->equal('type', 'Type')->select(['0' => 'iframe', '1' => 'window']);
          $filter->in('status')->multipleSelect(['0' => 'Pending Approval', '1' => 'approved/running' , '2' => 'Rejected', '3' => 'Paused', '4' => 'Completed', '5' => 'Admin Paused' ]);
          $filter->between('created_at', 'Ad Submitted Between')->datetime();
      });

        $grid->quickSearch(function ($model, $query) {
            $model->where('url', 'like', "%{$query}%")
            ->orWhere('title', 'like', "%{$query}%")
            ->orWhere('unique_id', 'like', "%{$query}%")
            ->orWhere('description', 'like', "%{$query}%")
            ;
        });



        $grid->model()->orderBy('id', 'desc');



        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\Ptc\Approve());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\Ptc\Pause());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\Ptc\Reject());
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
        $show = new Show(PtcAd::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('unique_id', __('Unique id'));
        $show->field('employer_id', __('Employer id'));
        $show->field('ad_balance', __('Ad balance'));
        $show->field('temp_locked_balance', __('Temp locked balance'));
        $show->field('reward_per_view', __('Reward per view'));
        $show->field('views_needed', __('Views needed'));
        $show->field('views_completed', __('Views completed'));
        $show->field('title', __('Title'));
        $show->field('description', __('Description'));
        $show->field('url', __('Url'));
        $show->field('seconds', __('Seconds'));
        $show->field('revision_interval', __('Revision interval'));
        $show->field('targeted_countries', __('Targeted countries'));
        $show->field('excluded_countries', __('Excluded countries'));
        $show->field('type', __('Type'));
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
        $form = new Form(new PtcAd());

        $form->text('unique_id', __('Unique id'))->value(bin2hex(random_bytes(15)));
        $form->number('employer_id', __('Employer id'));
        $form->decimal('ad_balance', __('Ad balance'))->default(0.0000);
        $form->decimal('temp_locked_balance', __('Temp locked balance'))->default(0.0000);
        $form->decimal('reward_per_view', __('Reward per view'))->default(0.0000);
        $form->number('views_needed', __('Views needed'));
        $form->number('views_completed', __('Views completed'));
        $form->text('title', __('Title'));
        $form->text('description', __('Description'));
        $form->url('url', __('Url'));
        $form->number('seconds', __('Seconds'));
        $form->number('revision_interval', __('Revision interval'))->default(24);
        $form->textarea('targeted_countries', __('Targeted countries'));
        $form->textarea('excluded_countries', __('Excluded countries'));
        $form->switch('type', __('Type: Window(1) iframe(0)'));
        $form->switch('status', __('Status'));

        

        return $form;
    }
}
