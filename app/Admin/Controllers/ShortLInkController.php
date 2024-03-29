<?php

namespace App\Admin\Controllers;

use OpenAdmin\Admin\Controllers\AdminController;
use OpenAdmin\Admin\Form;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Show;
use \App\Models\ShortLink;
use App\Models\ShortLinksHistory;

class ShortLinkController extends AdminController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    protected $title = 'ShortLink';

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        $grid = new Grid(new ShortLink());
        
        $grid->column('id', __('Id'))->sortable();
        $grid->column('order', __('Order'))->sortable()->text();
        $grid->column('name', __('Name'))->sortable()->text();
        $grid->column('status', __('Status'))->switch([
            'enable' => ['value' => 1, 'text' => 'open', 'color' => 'primary'],
            'disable' => ['value' => 0, 'text' => 'close', 'color' => 'default'],
        ])->sortable();
        $grid->column('total_views')->display( function(){
            $id = $this->id;
            $totalViews = ShortLinksHistory::where('link_id', $id)->count();
            return "<span>$totalViews</span>";
        })->sortable();
        $grid->column('total_paid')->display( function(){
            $id = $this->id;
            $totalPaid = ShortLinksHistory::where('link_id', $id)->sum('reward');
            return "<span>$totalPaid</span>";
        })->sortable();
        $grid->column('url', __('Url'))->text();
        $grid->column('unique_id', __('Unique id'));
        $grid->column('reward', __('Reward'))->sortable()->text();
        $grid->column('views_per_day', __('Views per day'))->sortable()->text();
        $grid->column('min_seconds', __('Min seconds'))->sortable()->text();
        $grid->column('created_at', __('Created at'));
        $grid->column('updated_at', __('Updated at'));

        $grid->disableActions();

        $grid->quickSearch(function ($model, $query) {
            $model->where('name', 'like', "%{$query}%")->orWhere('url', 'like', "%{$query}%");
        });

        $grid->quickCreate(function (Grid\Tools\QuickCreate $create) {
            $create->text('name', 'name')->placeholder('Name');
            $create->url('url', 'url')->placeholder('https://apiurl.com/?api=&url=[url]');
            $create->decimal('reward', 'reward')->placeholder('Reward eg, 0.001');
            $create->number('views_per_day', 'views_per_day')->placeholder('views 24h');
            $create->number('min_seconds', 'min_seconds')->placeholder('min seconds');
            $create->hidden('unique_id', 'unique_id')->value(bin2hex(random_bytes(16)));
        });

;
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
        $show = new Show(ShortLink::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('name', __('Name'));
        $show->field('url', __('Url'));
        $show->field('unique_id', __('Unique id'));
        $show->field('reward', __('Reward'));
        $show->field('views_per_day', __('Views per day'));
        $show->field('min_seconds', __('Min seconds'));
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
        $order = ShortLink::latest()->first();
        $form = new Form(new ShortLink());

        $form->number('order', __('Order'))->default($order->id++);
        $form->text('name', __('Name'));
        $form->switch('status', __('Status'))->default(1);
        $form->url('url', __('Url'));
        $form->text('unique_id', __('Unique id'))->value(bin2hex(random_bytes(16)));
        $form->decimal('reward', __('Reward'))->default(0.0000);
        $form->number('views_per_day', __('Views per day'))->default(1);
        $form->number('min_seconds', __('Min seconds'))->default(10);

        return $form;
    }
}
