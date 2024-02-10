<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\OffersAndSurveysLog;
use App\Models\Offerwall;
use App\Models\User;

class OffersAndSurveysLogsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OffersAndSurveysLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OffersAndSurveysLog());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('user_id', __('User id'))->sortable();
        $grid->column('provider_name', __('Provider name'))->sortable();
        $grid->column('payout', __('Payout'))->sortable();
        $grid->column('reward', __('Reward'))->sortable();
        $grid->column('added_expert_level', __('Added expert level'))->sortable();
        $grid->column('upline_commision', __('Upline commision'))->sortable();
        $grid->column('transaction_id', __('Transaction id'));
        $grid->column('offer_id', __('Offer id'))->sortable();
        $grid->column('offer_name', __('Offer name'))->sortable();
        $grid->column('hold_time', __('Hold time'))->sortable();
        $grid->column('instant_credit', __('Instant credit'));
        $grid->column('ip_address', __('Ip address'));
        $grid->column('status', __('Status'))->display(function ($status) {
            switch ($status) {
                case 0:
                  return "<span class='badge bg-success'>Completed</span>";
                  break;
                case 1:
                    return "<span class='badge bg-warning'>On Hold</span>";
                  break;
                case 2:
                    return "<span class='badge bg-danger'>Reversed</span>";
                  break;
                default:
                return "<span class='badge bg-primary'>$status</span>";
              }
        
        })->sortable();
        $grid->column('created_at', __('Created at'))->sortable();
        $grid->column('updated_at', __('Updated at'))->sortable();

        $grid->model()->orderBy('updated_at', 'desc');


        $grid->filter(function($filter){

            // Add a column filter
            $offerwalls = Offerwall::pluck('name', 'name')->toArray();
            $filter->like('name', 'name');
            $filter->equal('user_id', 'User ID');
            $filter->in('status', 'Status')->multipleSelect(['0' => 'Completed', '1' => 'Pending' , '2' => 'Reversed']);
            $filter->in('provider_name', 'Offerwall')->multipleSelect($offerwalls);
        });

        $grid->quickSearch(function ($model, $query) {
            $model->where('provider_name', 'like', "%{$query}%")
            ->orWhere('status', 'like', "%{$query}%")
            ->orWhere('user_id', 'like', "%{$query}%");
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
        $show = new Show(OffersAndSurveysLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('provider_name', __('Provider name'));
        $show->field('payout', __('Payout'));
        $show->field('reward', __('Reward'));
        $show->field('added_expert_level', __('Added expert level'));
        $show->field('upline_commision', __('Upline commision'));
        $show->field('transaction_id', __('Transaction id'));
        $show->field('offer_id', __('Offer id'));
        $show->field('offer_name', __('Offer name'));
        $show->field('hold_time', __('Hold time'));
        $show->field('instant_credit', __('Instant credit'));
        $show->field('ip_address', __('Ip address'));
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
        $form = new Form(new OffersAndSurveysLog());

        $form->number('user_id', __('User id'));
        $form->text('provider_name', __('Provider name'));
        $form->decimal('payout', __('Payout'))->default(0.0000);
        $form->decimal('reward', __('Reward'))->default(0.0000);
        $form->decimal('added_expert_level', __('Added expert level'))->default(0.0000);
        $form->decimal('upline_commision', __('Upline commision'))->default(0.0000);
        $form->text('transaction_id', __('Transaction id'));
        $form->text('offer_id', __('Offer id'));
        $form->text('offer_name', __('Offer name'));
        $form->number('hold_time', __('Hold time'));
        $form->switch('instant_credit', __('Instant credit'));
        $form->text('ip_address', __('Ip address'));
        $form->number('status', __('Status'));

        return $form;
    }
}
