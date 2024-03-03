<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\WithdrawalHistory;
use App\Admin\Actions\Withdrawals\Approve;
use App\Admin\Actions\Withdrawals\SingleApprove;
use App\Admin\Actions\Withdrawals\SingleCancel;
use App\Admin\Actions\Withdrawals\SingleRefund;
use App\Models\PayoutGateway;
use App\Models\User;

class WithdrawalHistoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'WithdrawalHistory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new WithdrawalHistory());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('user_id', __('Username'))->display( function($userid){
            $username = User::where('id', $userid)->value('username');
            return "<span>$username</span>";
        })->sortable();    
                  
        $grid->column('method', __('Method'))->sortable();
        $grid->column('wallet', __('Wallet'))->sortable();
        $grid->column('amount_after_fee', __('Amount after fee'))->sortable();
        $grid->column('status', __('Status'))->display( function($status){
            switch ($status) {
                case 0:
                  return "<span class='badge bg-warning'>Pending</span>";
                  break;
                case 1:
                    return "<span class='badge bg-success'>Completed</span>";
                    break;
                case 2:
                    return "<span class='badge bg-primary'>Refunded</span>";
                    break;
                case 3:
                    return "<span class='badge bg-danger'>Cancelled</span>";
                    break;
                case 4:
                    return "<span class='badge bg-danger'>Investigate</span>";
                    break;                 
                default:
                return "<span class='badge bg-primary'>$status</span>";
              }
        })->sortable();
        $grid->column('description', __('Description'));
        $grid->column('created_at', __('Submitted'))->diffForHumans();
        $grid->column('updated_at', __('Last Update'))->diffForHumans();


        $grid->model()->orderByRaw('CASE WHEN status = 0 THEN 0 ELSE 1 END');


        $grid->filter(function($filter){

            // Add a column filter
            $methods = PayoutGateway::pluck('name', 'name')->toArray();
            $filter->like('user_id', 'User ID');
            $filter->where(function ($query) {
                $query->whereHas('user', function ($query) {
                    $query->where('username', 'like', "%{$this->input}%");
                });
            }, 'Username');
            $filter->in('status', 'Status')->multipleSelect(['0' => 'Pending', '1' => 'Completed' , '2' => 'Refunded', '3' => 'Cancelled']);
            $filter->in('method', 'method')->multipleSelect($methods);
        });

        $grid->quickSearch(function ($model, $query) {
            $model->where('method', 'like', "%{$query}%")
            ->orWhere('status', 'like', "%{$query}%")
            ->orWhere('user_id', 'like', "%{$query}%");
        });

        $grid->actions(function ($actions) {
            $actions->disableDelete();
            $actions->disableEdit();
            $actions->disableShow();
            $actions->add(new SingleApprove);
            $actions->add(new SingleCancel);
            $actions->add(new SingleRefund);
          });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\Withdrawals\BatchApprove());
            $tools->append(new \App\Admin\Actions\Withdrawals\BatchCancel());
            $tools->append(new \App\Admin\Actions\Withdrawals\BatchRefund());
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
        $show = new Show(WithdrawalHistory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('method', __('Method'));
        $show->field('wallet', __('Wallet'));
        $show->field('amount_no_fee', __('Amount no fee'));
        $show->field('amount_after_fee', __('Amount after fee'));
        $show->field('fee', __('Fee'));
        $show->field('status', __('Status'));
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
        $form = new Form(new WithdrawalHistory());

        $form->number('user_id', __('User id'));
        $form->text('method', __('Method'));
        $form->textarea('wallet', __('Wallet'));
        $form->decimal('amount_no_fee', __('Amount no fee'));
        $form->decimal('amount_after_fee', __('Amount after fee'));
        $form->decimal('fee', __('Fee'));
        $form->number('status', __('Status'));
        $form->textarea('description', __('Description'));

        return $form;
    }
}
