<?php

namespace App\Admin\Actions\User;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\User;

class SuspendUserAction extends Action
{
    protected $selector = '.suspend-user-action';

    public function handle(Request $request)
    {
        
        $keys = explode(',', $request->input('_key'));

        // store each selected user in users
        $users = User::whereIn('id', $keys)->get();

        //suspend each user i.e put status to 2
        foreach ($users as $user) {
            $user->update(['status' => 2]);
        }
        return $this->response()->success('Selected Users Suspended Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='suspend-user-action btn btn-sm btn-danger show-on-rows-selected d-none me-1'>Suspend</a>";
    }
}