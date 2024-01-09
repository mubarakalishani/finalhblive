<?php

namespace App\Admin\Actions\Task;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\Task;

class Approve extends Action
{
    protected $selector = '.approve';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected task in tasks
        $tasks = Task::whereIn('id', $keys)->get();

        //approve each task by admin i.e put status to 1
        foreach ($tasks as $task) {
            $task->update(['status' => 1]);
        }
        return $this->response()->success('Selected Task Paused Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='approve btn btn-sm btn-success show-on-rows-selected d-none me-1 mt-1 mb-1'>Approve</a>";
    }
}