<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\FaucetClaim;
use Illuminate\Support\Carbon;


class FaucetClaimController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'FaucetClaim';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new FaucetClaim());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('user_id', __('User id'))->sortable();
        $grid->column('claimed_amount', __('Claimed amount'))->sortable();
        $grid->column('created_at', __('Claimed at'))->display(function ($value) {
            return Carbon::parse($value)->format('d-M-Y H:i:s');
        });
        $grid->column('updated_at', __('Updated at'))->display(function ($value) {
            return Carbon::parse($value)->format('d-M-Y H:i:s');
        });

        $grid->disableActions();
        
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
        $show = new Show(FaucetClaim::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('user_id', __('User id'));
        $show->field('claimed_amount', __('Claimed amount'));
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
        $form = new Form(new FaucetClaim());

        $form->number('user_id', __('User id'));
        $form->decimal('claimed_amount', __('Claimed amount'))->default(0.0000);

        return $form;
    }
}
