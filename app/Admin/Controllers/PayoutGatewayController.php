<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PayoutGateway;

class PayoutGatewayController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PayoutGateway';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PayoutGateway());

        $grid->column('id', __('Id'));
        $grid->column('name', __('Name'));
        $grid->column('min_payout', __('Min payout'));
        $grid->column('fixed_fee', __('Fixed fee'));
        $grid->column('fee_percentage', __('Fee percentage'));
        $grid->column('instant', __('Instant'))->switch([
            'enable' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
            'disable' => ['value' => 0, 'text' => 'close', 'color' => 'default'],
        ]);
        $grid->column('status', __('Status'))->switch([
            'enable' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
            'disable' => ['value' => 0, 'text' => 'close', 'color' => 'default'],
        ]);

        $grid->column('image_path', __('Image url'));

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
        $show = new Show(PayoutGateway::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('image_path', __('Image url'));
        $show->field('min_payout', __('Min payout'));
        $show->field('fixed_fee', __('Fixed fee'));
        $show->field('fee_percentage', __('Fee percentage'));
        $show->field('instant', __('Instant'));
        $show->field('status', __('Status'));

        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        $form = new Form(new PayoutGateway());

        $form->text('name', __('Name'));
        $form->text('image_path', __('Image url'));
        $form->decimal('min_payout', __('Min payout'))->default(0.000);
        $form->decimal('fixed_fee', __('Fixed fee'))->default(0.000);
        $form->number('fee_percentage', __('Fee percentage'))->default(0);
        $form->switch('instant', __('Instant'))->default(0);
        $form->switch('status', __('Status'))->default(1);


        return $form;
    }
}
