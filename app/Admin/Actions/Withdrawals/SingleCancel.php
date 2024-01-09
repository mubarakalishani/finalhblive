<?php

namespace App\Admin\Actions\Withdrawals;
use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;

class SingleCancel extends RowAction
{
    public $name = 'Single Cancel';

    public $icon = 'icon-times-circle text-danger';


    public function handle(Model $model)
    {
        $model->update(['status' => 3]);

        return $this->response()->success('The selected Withdrawal has been cancelled successfully.')->refresh();
    }
}