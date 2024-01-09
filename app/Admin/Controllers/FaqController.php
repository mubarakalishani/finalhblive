<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\Faq;

class FaqController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'Faq';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new Faq());

        $grid->column('id', __('Id'))->sortable();
        $grid->column('s_no', __('S#'))->sortable()->number();
        $grid->column('question', __('Question'))->textarea();
        $grid->column('answer', __('Answer'))->textarea();

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
        $show = new Show(Faq::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('s_no', __('S#'));
        $show->field('question', __('Question'));
        $show->field('answer', __('Answer'));
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
        // Retrieve the last serial number from the database
        $lastSerialNumber = Faq::orderBy('s_no', 'desc')->value('s_no');

        // Calculate the default value for the new record
        $defaultValue = $lastSerialNumber + 1;
        $form = new Form(new Faq());
        $form->number('s_no', __('Serial No/Q#'))->default($defaultValue);
        $form->textarea('question', __('Question'));
        $form->textarea('answer', __('Answer'));

        return $form;
    }
}
