<?php

namespace App\Admin\Actions\Task;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\Task;

class AdminStop extends Action
{
    protected $selector = '.admin-stop';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected task in tasks
        $tasks = Task::whereIn('id', $keys)->get();

        //stop each task by admin i.e put status to 8
        foreach ($tasks as $task) {
            $task->update(['status' => 8]);
        }
        return $this->response()->success('Selected Task Paused Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='admin-stop btn btn-sm btn-dark show-on-rows-selected d-none me-1 mt-1 mb-1'>Stop</a>";
    }
}