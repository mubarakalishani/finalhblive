<?php

namespace App\Admin\Controllers;
use App\Admin\Actions\BatchReplicate;
use App\Admin\Actions\User\SuspendUserAction;
use App\Admin\Actions\User\UnsuspendUser;
use App\Admin\Actions\User\AddUserBalance;
use App\Admin\Actions\User\SubtractUserBalance;
use App\Admin\Actions\User\BalanceAdd;
use OpenAdmin\Admin\Grid\Displayers\Actions\DropdownActions;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\User;
use App\Models\AvailableCountry;

class UserController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'User';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new User());
        $grid->fixColumns(3);
        $grid->column('id', __('Id'))->sortable();
        $grid->column('username', __('Username'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('balance', __('Balance'))->sortable();
        $grid->column('deposit_balance', __('Deposit balance'))->sortable();
        $grid->column('upline', __('Upline'));
        $grid->column('country', __('Country'))->sortable();
        $grid->column('referrals', __('Referrals'))->sortable();
        $grid->column('unique_user_id', __('Unique user id'));
        $grid->column('email', __('Email'));
        $grid->column('signup_ip', __('Signup ip'));
        $grid->column('last_ip', __('Last ip'));
        $grid->column('level', __('Level'))->display( function($level){
            switch ($level) {
                case 0:
                  return "<span class='badge bg-warning'>Starter</span>";
                  break;
                case 1:
                    return "<span class='badge bg-info'>Advance</span>";
                  break;
                case 2:
                    return "<span class='badge bg-success'>Expert</span>";
                  break;
                default:
                return "<span class='badge bg-primary'>$level</span>";
              }
        });
        $grid->column('utm_source', __('Utm source'));
        $grid->column('bonus_balance', __('Bonus balance'));
        $grid->column('diamond_level_balance', __('Diamond level balance'));
        $grid->column('instant_withdrawable_balance', __('Instant withdrawable balance'));
        $grid->column('total_earned', __('Total earned'));
        $grid->column('earned_from_referrals', __('Earned from referrals'));
        $grid->column('earned_from_offers', __('Earned from offers'));
        $grid->column('earned_from_tasks', __('Earned from tasks'));
        $grid->column('earned_from_surveys', __('Earned from surveys'));
        $grid->column('earned_from_ptc', __('Earned from ptc'));
        $grid->column('earned_from_faucet', __('Earned from faucet'));
        $grid->column('earned_from_shortlinks', __('Earned from shortlinks'));
        $grid->column('total_tasks_completed', __('Total tasks completed'));
        $grid->column('total_offers_completed', __('Total offers completed'));
        $grid->column('total_surveys_completed', __('Total surveys completed'));
        $grid->column('total_faucet_completed', __('Total faucet completed'));
        $grid->column('total_shortlinks_completed', __('Total shortlinks completed'));
        $grid->column('kyc_status', __('Kyc status'))->display(function($kyc_status){
            switch ($kyc_status) {
                case 0:
                    return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill text-danger" viewBox="0 0 16 16">
                    <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                  </svg>';
                    break;
                case 1:
                    return '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-patch-check-fill text-primary" viewBox="0 0 16 16">
                    <path d="M10.067.87a2.89 2.89 0 0 0-4.134 0l-.622.638-.89-.011a2.89 2.89 0 0 0-2.924 2.924l.01.89-.636.622a2.89 2.89 0 0 0 0 4.134l.637.622-.011.89a2.89 2.89 0 0 0 2.924 2.924l.89-.01.622.636a2.89 2.89 0 0 0 4.134 0l.622-.637.89.011a2.89 2.89 0 0 0 2.924-2.924l-.01-.89.636-.622a2.89 2.89 0 0 0 0-4.134l-.637-.622.011-.89a2.89 2.89 0 0 0-2.924-2.924l-.89.01-.622-.636zm.287 5.984-3 3a.5.5 0 0 1-.708 0l-1.5-1.5a.5.5 0 1 1 .708-.708L7 8.793l2.646-2.647a.5.5 0 0 1 .708.708z"/>
                  </svg>';
                    break;    
                
                default:
                    # code...
                    break;
            }
        });
        $grid->column('created_at', __('SignUp Date'));
        $grid->column('updated_at', __('Last Activity'));
        $grid->column('status', __('Status'))->display(function ($status) {
            switch ($status) {
                case 0:
                  return "<span class='badge bg-secondary'>inactive</span>";
                  break;
                case 1:
                    return "<span class='badge bg-success'>Active</span>";
                  break;
                case 2:
                    return "<span class='badge bg-danger'>Suspended</span>";
                  break;
                case 3:
                    return "<span class='badge bg-warning'>Suspicious</span>";
                  break;
                default:
                return "<span class='badge bg-primary'>$status</span>";
              }
        
        });

        // $grid->setActionClass(DropdownActions::class);
        
        $grid->quickSearch(function ($model, $query) {
            $model->where('name', 'like', "%{$query}%")
            ->orWhere('username', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%");
        });

        //fix header of the table at the top of the screen
        $grid->fixHeader();


        $grid->filter(function($filter){

            // Add a column filter
            $countries = AvailableCountry::pluck('country_name', 'country_name')->toArray();
            $filter->like('name', 'name');
            $filter->like('username', 'username');
            $filter->equal('upline', 'Upline Id');
            $filter->like('unique_user_id', 'Unique User Id');
            $filter->like('email', 'email');
            $filter->like('signup_ip', 'signup ip');
            $filter->like('last ip', 'signup ip');
            $filter->in('status')->multipleSelect(['1' => 'Active', '0' => 'Inactive' , '2' => 'Suspended', '3' => 'Suspicious' ]);
            $filter->in('country', 'Country')->multipleSelect($countries);
            $filter->between('created_at', 'Signed Up Between')->datetime();
        });


        $grid->batchActions(function ($batch) {
            $batch->add(new BatchReplicate());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\User\SuspendUserAction());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\User\UnsuspendUser());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\User\AddUserBalance());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\User\SubtractUserBalance());
        });

        $grid->actions(function ($actions) {
            $actions->add(new BalanceAdd());
          });

          $grid->model()->orderBy('id', 'desc');


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
        $show = new Show(User::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('username', __('Username'));
        $show->field('unique_user_id', __('Unique user id'));
        $show->field('email', __('Email'));
        $show->field('level', __('Level'));
        $show->field('secret_key', __('Secret key'));
        $show->field('referrals', __('Referrals'));
        $show->field('utm_source', __('Utm source'));
        $show->field('signup_ip', __('Signup ip'));
        $show->field('last_ip', __('Last ip'));
        $show->field('upline', __('Upline'));
        $show->field('country', __('Country'));
        $show->field('balance', __('Balance'));
        $show->field('deposit_balance', __('Deposit balance'));
        $show->field('bonus_balance', __('Bonus balance'));
        $show->field('diamond_level_balance', __('Diamond level balance'));
        $show->field('instant_withdrawable_balance', __('Instant withdrawable balance'));
        $show->field('total_earned', __('Total earned'));
        $show->field('earned_from_referrals', __('Earned from referrals'));
        $show->field('earned_from_offers', __('Earned from offers'));
        $show->field('earned_from_tasks', __('Earned from tasks'));
        $show->field('earned_from_surveys', __('Earned from surveys'));
        $show->field('earned_from_ptc', __('Earned from ptc'));
        $show->field('earned_from_faucet', __('Earned from faucet'));
        $show->field('earned_from_shortlinks', __('Earned from shortlinks'));
        $show->field('total_tasks_completed', __('Total tasks completed'));
        $show->field('total_offers_completed', __('Total offers completed'));
        $show->field('total_surveys_completed', __('Total surveys completed'));
        $show->field('total_faucet_completed', __('Total faucet completed'));
        $show->field('total_shortlinks_completed', __('Total shortlinks completed'));
        $show->field('status', __('Status'));
        
        $show->field('kyc_status', __('Kyc status'));
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
        $form = new Form(new User());

        $form->text('name', __('Name'));
        $form->text('username', __('Username'));
        $form->email('email', __('Email'));
        $form->number('level', __('Level'));
        $form->select('status','status')->options([0 => 'inactive', 1 => 'Active', 2 => 'suspended', 3 => 'suspicious']);
        $form->switch('kyc_status', __('Kyc status'));
        $form->text('remember_token', __('Remember token'));
        $form->number('current_team_id', __('Current team id'));
        $form->text('profile_photo_path', __('Profile photo path'));

        return $form;
    }



    
}
