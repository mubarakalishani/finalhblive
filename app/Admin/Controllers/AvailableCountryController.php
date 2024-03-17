<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\AvailableCountry;
use App\Models\User;

class AvailableCountryController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'AvailableCountry';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new AvailableCountry());

        $grid->column('id', __('Id'));
        $grid->column('country_code', __('Country code'));
        $grid->column('country_name', __('Country name'));
        $grid->column('total_users')->display( function(){
            $id = $this->country_name;
            $totalUsers = User::where('country', $id)->count();
            return "<span>$totalUsers</span>";;
        })->sortable();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\Country\AddToTier1());
        });

        $grid->tools(function (Grid\Tools $tools) {
            $tools->append(new \App\Admin\Actions\Country\AddToTier2());
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
        $show = new Show(AvailableCountry::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('country_code', __('Country code'));
        $show->field('country_name', __('Country name'));
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
        $form = new Form(new AvailableCountry());

        $form->text('country_code', __('Country code'));
        $form->text('country_name', __('Country name'));

        return $form;
    }
}
