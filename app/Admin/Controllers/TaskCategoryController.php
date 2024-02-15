<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\TaskCategory;

class TaskCategoryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'TaskCategory';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new TaskCategory());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('name', __('Name'))->sortable()->text();
        $grid->column('parent_id', __('Parent id'))->sortable();
        $grid->column('min_reward', __('Min reward'))->sortable()->text();

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $categories = TaskCategory::whereNull('parent_id')->pluck('name', 'id')->toArray();
            $create->select('parent_id', __('Parent Category'))->options($categories)->required();
            $create->text('name', __('Sub Category Name'))->required();
            $create->decimal('min_reward', __('Min reward'))->default(0.000);
        });

        $grid->quickSearch(function ($model, $query) {
            $model->where('name', 'like', "%{$query}%");
        });


        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\TaskCategory\AddCountryRewards());
        });


        $grid->filter(function($filter){

            // Add a column filter
            $parentCategories = TaskCategory::whereNull('parent_id')->pluck('name', 'id')->toArray();
            $filter->like('name', 'Category Name');
            $filter->equal('parent_id', 'Category')->select($parentCategories);
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
        $show = new Show(TaskCategory::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('parent_id', __('Parent id'));
        $show->field('min_reward', __('Min reward'));
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
        $form = new Form(new TaskCategory());
        $categories = TaskCategory::whereNull('parent_id')->pluck('name', 'id')->toArray();
        $form->text('name', __('Sub Category Name'));
        $form->select('parent_id', __('Parent Category'))->options($categories)->required();
        $form->decimal('min_reward', __('Min reward'))->default(0.000);
        return $form;
    }
}
