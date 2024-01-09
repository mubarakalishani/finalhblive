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
        $grid->column('order', __('Order'));
        $grid->column('name', __('Name'));
        $grid->column('status', __('Status'))->switch([
            'enable' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
            'disable' => ['value' => 0, 'text' => 'close', 'color' => 'default'],
        ]);
        $grid->column('iframe_url', __('Iframe url'));
        $grid->column('iframe_styles', __('Iframe styles'));
        $grid->column('iframe_extra_elements', __('Iframe extra elements'));
        $grid->column('is_target_blank', __('Is target blank'))->switch([
            'enable' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
            'disable' => ['value' => 0, 'text' => 'close', 'color' => 'default'],
        ]);
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
    {   // Retrieve the last serial number from the database
        $lastSerialNumber = Offerwall::orderBy('order', 'desc')->value('order');

        // Calculate the default value for the new record
        $defaultValue = $lastSerialNumber + 1;
        $form = new Form(new Offerwall());

        $form->number('order', __('Order'))->default($defaultValue);
        $form->text('name', __('Name'));
        $form->switch('status', __('Status'))->default(1);
        $form->text('iframe_url', __('Iframe url'));
        $form->textarea('iframe_styles', __('Iframe styles'));
        $form->textarea('iframe_extra_elements', __('Iframe extra elements'));
        $form->switch('is_target_blank', __('Is target blank'));
        $form->text('image_url', __('Image url'));
        $form->textarea('image_styles', __('Image styles'));

        return $form;
    }
}
