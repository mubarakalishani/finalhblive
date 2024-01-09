<?php

namespace App\Admin\Actions\User;

use Illuminate\Database\Eloquent\Model;
use OpenAdmin\Admin\Actions\RowAction;
use Illuminate\Http\Request;
use App\Models\User;

class BalanceAdd extends RowAction
{
    public $name = 'AddBalance';

    public $icon = 'icon-AddBalance';

    public function form()
    {
        $this->text('balance', 'balance')->rules('required|numeric|min:0')
            ->placeholder('Enter the amount to add');
    }

    public function handle(User $model, Request $request)
    {
        $model->balance += $request->get('balance');
        $model->save();

        return $this->response()->success('Success message.')->refresh();
    }

    // The form fields for adding user balance
    

}