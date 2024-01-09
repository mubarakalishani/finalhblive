<?php

namespace App\Admin\Actions\Task;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\Task;

class AdminPause extends Action
{
    protected $selector = '.admin-pause';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected task in tasks
        $tasks = Task::whereIn('id', $keys)->get();

        //Pause each task by admin i.e put status to 2
        foreach ($tasks as $task) {
            $task->update(['status' => 7]);
        }
        return $this->response()->success('Selected Task Paused Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='admin-pause btn btn-sm btn-secondary show-on-rows-selected d-none me-1 mt-1 mb-1'>Pause</a>";
    }
}