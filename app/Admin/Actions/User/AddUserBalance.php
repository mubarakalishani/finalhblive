<?php

namespace App\Admin\Actions\User;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\User;

class AddUserBalance extends Action
{
    public $name = 'Add User Balance';
    protected $selector = '.add-user-balance';

    // The form fields for adding user balance
    public function form()
    {
        $this->text('balance', 'Balance')->rules('required|numeric|min:0')
            ->placeholder('Enter the amount to add');
        
    }

    // The HTML for the action button
    public function html()
    {
        return "<a class='add-user-balance btn btn-sm btn-secondary show-on-rows-selected d-none me-1'>
                    <i class='icon-wallet'></i><i class='icon-plus'></i>
                </a>";
    }

    // Handling the form submission
    public function handle(\App\Models\User $model, Request $request)
    {
        // dd($request->all());
        // $model->balance += $request->input('balance');
        // $model->save();

        return $this->response()->success('Balance added successfully.')->refresh();
    }
}
