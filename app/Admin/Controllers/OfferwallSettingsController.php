<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\OfferwallsSetting;

class OfferwallSettingsController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'OfferwallsSetting';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new OfferwallsSetting());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('value', __('Value'))->sortable()->text();

        $grid->filter(function($filter){
            $filter->like('name', 'name');
            $filter->like('value', 'value');
        });

        $grid->quickSearch(function ($model, $query) {
            $model->where('name', 'like', "%{$query}%")->orWhere('value', 'like', "%{$query}%");
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
        $show = new Show(OfferwallsSetting::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('value', __('Value'));
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
        $form = new Form(new OfferwallsSetting());

        $form->text('name', __('Name'));
        $form->textarea('value', __('Value'));

        return $form;
    }
}
