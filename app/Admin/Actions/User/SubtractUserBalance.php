<?php

namespace App\Admin\Actions\User;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;

class SubtractUserBalance extends Action
{
    protected $selector = '.subtract-user-balance';

    public function handle(Request $request)
    {
        // $request ...

        return $this->response()->success('Success message...')->refresh();
    }

    public function html()
    {
        return "<a class='subtract-user-balance btn btn-sm btn-secondary show-on-rows-selected d-none me-1'><i class='icon-wallet'></i><i class='icon-minus'></i></a>";
    }
}