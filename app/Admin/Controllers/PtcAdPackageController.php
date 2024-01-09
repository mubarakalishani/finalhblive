<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PtcAdPackage;

class PtcAdPackageController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PtcAdPackage';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PtcAdPackage());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable();
        $grid->column('reward_per_view', __('Reward per view'))->sortable();
        $grid->column('seconds', __('Seconds'))->sortable();
        $grid->column('minimum_views', __('Minimum views'))->sortable();

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
        $show = new Show(PtcAdPackage::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('reward_per_view', __('Reward per view'));
        $show->field('seconds', __('Seconds'));
        $show->field('minimum_views', __('Minimum views'));
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
        $form = new Form(new PtcAdPackage());

        $form->text('name', __('Name'));
        $form->decimal('reward_per_view', __('Reward per view'))->default(0.00000);
        $form->number('seconds', __('Seconds'))->default(5);
        $form->number('minimum_views', __('Minimum views'));

        return $form;
    }
}
