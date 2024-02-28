<?php

namespace App\Admin\Actions\Withdrawals;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;

class SingleApprove extends RowAction
{
    public $name = 'Single Approve';

    public $icon = 'icon-check-circle text-success';

    public function handle(Model $model)
    {
        $model->update(['status' => 1]);
        $model->user->update([
            'total_withdrawn' => $model->amount_after_fee
        ]);

        return $this->response()->success('Successfully Completed')->refresh();
    }

}