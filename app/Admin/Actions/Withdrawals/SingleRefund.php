<?php

namespace App\Admin\Actions\Withdrawals;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;
class SingleRefund extends RowAction
{
    public $name = 'Single Refund';

    public $icon = 'icon-history text-primary';

    public function handle(Model $model)
    {
        $model->update(['status' => 2]);
        $user = User::find($model->user_id);
        $user->addWorkerBalance($model->amount_no_fee);
        return $this->response()->success('Success message.')->refresh();
    }

}