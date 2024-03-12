<?php

namespace App\Admin\Actions\Withdrawals;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;

class SingleApprove extends RowAction
{
    public $name = 'Single Approve';

    public $icon = 'icon-check-circle text-success';

    public function handle(Model $model)
    {
        if ($model->status == 2) {
            $user = User::find($model->user_id);
            $user->decrement('balance', $model->amount_no_fee);
            $user->update([
                'total_withdrawn' => $model->amount_after_fee
            ]);
            $model->update(['status' => 1]);
        }
        else{
            $model->update(['status' => 1]);
            $user = User::find($model->user_id);
            $user->update([
                'total_withdrawn' => $model->amount_no_fee
            ]);
        }

        return $this->response()->success('Successfully Completed')->refresh();
    }

}