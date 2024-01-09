<?php

namespace App\Admin\Actions\User;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\User;

class UnsuspendUser extends Action
{
    protected $selector = '.unsuspend-user';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected user in users
        $users = User::whereIn('id', $keys)->get();

        //un-suspend each user i.e put status to 1 which means active
        foreach ($users as $user) {
            $user->update(['status' => 1]);
        }
        return $this->response()->success('Selected Users Suspended Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='unsuspend-user btn btn-sm btn-info show-on-rows-selected d-none me-1'>Un Suspend</a>";
    }
}