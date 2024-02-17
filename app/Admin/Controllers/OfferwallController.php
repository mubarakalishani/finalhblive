<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Offerwall;

class OfferwallController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Offerwall';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Offerwall());

        $grid->column('id', __('Id'));
        $grid->column('order', __('Order'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('status', __('Status'))->sortable();
        $grid->column('secret_key', __('Secret key'));
        $grid->column('api_key', __('Api key'));
        $grid->column('whitelisted_ips', __('Whitelisted ips'));
        $grid->column('starter_cp', __('Starter cp'))->sortable();
        $grid->column('advance_cp', __('Advance cp'))->sortable();
        $grid->column('expert_cp', __('Expert cp'))->sortable();
        $grid->column('ref_commission', __('Ref commission'))->sortable();
        $grid->column('tier1_hold_amount', __('Tier1 hold amount'))->sortable();
        $grid->column('tier1_hold_time', __('Tier1 hold time'))->sortable();
        $grid->column('tier2_hold_amount', __('Tier2 hold amount'))->sortable();
        $grid->column('tier2_hold_time', __('Tier2 hold time'))->sortable();
        $grid->column('tier3_hold_amount', __('Tier3 hold amount'))->sortable();
        $grid->column('tier3_hold_time', __('Tier3 hold time'))->sortable();
        $grid->column('hold', __('Hold'))->sortable();
        $grid->column('iframe_url', __('Iframe url'));
        $grid->column('iframe_styles', __('Iframe styles'));
        $grid->column('iframe_extra_elements', __('Iframe extra elements'));
        $grid->column('is_target_blank', __('Is target blank'));
        $grid->column('image_url', __('Image url'));
        $grid->column('image_styles', __('Image styles'));
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
        $show = new Show(Offerwall::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('order', __('Order'));
        $show->field('name', __('Name'));
        $show->field('status', __('Status'));
        $show->field('secret_key', __('Secret key'));
        $show->field('api_key', __('Api key'));
        $show->field('whitelisted_ips', __('Whitelisted ips'));
        $show->field('starter_cp', __('Starter cp'));
        $show->field('advance_cp', __('Advance cp'));
        $show->field('expert_cp', __('Expert cp'));
        $show->field('ref_commission', __('Ref commission'));
        $show->field('tier1_hold_amount', __('Tier1 hold amount'));
        $show->field('tier1_hold_time', __('Tier1 hold time'));
        $show->field('tier2_hold_amount', __('Tier2 hold amount'));
        $show->field('tier2_hold_time', __('Tier2 hold time'));
        $show->field('tier3_hold_amount', __('Tier3 hold amount'));
        $show->field('tier3_hold_time', __('Tier3 hold time'));
        $show->field('hold', __('Hold'));
        $show->field('iframe_url', __('Iframe url'));
        $show->field('iframe_styles', __('Iframe styles'));
        $show->field('iframe_extra_elements', __('Iframe extra elements'));
        $show->field('is_target_blank', __('Is target blank'));
        $show->field('image_url', __('Image url'));
        $show->field('image_styles', __('Image styles'));
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
        $form = new Form(new Offerwall());

        $form->number('order', __('Order'));
        $form->text('name', __('Name'));
        $form->switch('status', __('Status'))->default(1);
        $form->text('secret_key', __('Secret key'));
        $form->text('api_key', __('Api key'));
        $form->textarea('whitelisted_ips', __('Whitelisted ips'));
        $form->number('starter_cp', __('Starter cp'))->default(30);
        $form->number('advance_cp', __('Advance cp'))->default(50);
        $form->number('expert_cp', __('Expert cp'))->default(80);
        $form->number('ref_commission', __('Ref commission'))->default(7);
        $form->decimal('tier1_hold_amount', __('Tier1 hold amount'))->default(0.5);
        $form->number('tier1_hold_time', __('Tier1 hold time'))->default(30);
        $form->decimal('tier2_hold_amount', __('Tier2 hold amount'))->default(1);
        $form->number('tier2_hold_time', __('Tier2 hold time'))->default(30);
        $form->decimal('tier3_hold_amount', __('Tier3 hold amount'))->default(5);
        $form->number('tier3_hold_time', __('Tier3 hold time'))->default(30);
        $form->switch('hold', __('Hold'))->default(1);
        $form->text('iframe_url', __('Iframe url'));
        $form->textarea('iframe_styles', __('Iframe styles'));
        $form->textarea('iframe_extra_elements', __('Iframe extra elements'));
        $form->switch('is_target_blank', __('Is target blank'));
        $form->text('image_url', __('Image url'));
        $form->textarea('image_styles', __('Image styles'));

        return $form;
    }
}
