<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\PtcLog;
use \App\Models\User;

class PtcLogController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'PtcLog';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new PtcLog());

        $grid->column('id', __('Id'));
        $grid->column('worker_id', __('Worker id'))->display(function ($worker_id, $column) {
            $user = User::find($worker_id);
            $username = $user->value('username');
            return $username;
        });

        $grid->column('ad_id', __('Ad id'))->sortable();
        $grid->column('reward', __('Reward'))->sortable();
        $grid->column('ip', __('Ip'))->sortable();
        $grid->column('created_at', __('Completed At'));

        $grid->quickSearch(function ($model, $query) {
            $model->where('ip', 'like', "%{$query}%")->orWhere('ad_id', 'like', "%{$query}%");
        });

        $grid->filter(function($filter){
            $filter->like('ip', 'ip');
            $filter->between('created_at', 'completed between')->datetime();
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
        $show = new Show(PtcLog::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('worker_id', __('Worker id'));
        $show->field('ad_id', __('Ad id'));
        $show->field('reward', __('Reward'));
        $show->field('ip', __('Ip'));
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
        $form = new Form(new PtcLog());

        $form->number('worker_id', __('Worker id'));
        $form->number('ad_id', __('Ad id'));
        $form->decimal('reward', __('Reward'));
        $form->ip('ip', __('Ip'));

        return $form;
    }
}
