<?php

namespace App\Http\Controllers\Advertiser;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskCategory;

class TaskFullPageController extends Controller
{
    public function showTask($taskId)
    {
        $task = Task::findOrFail($taskId); // Assuming you have a Task model
        $category = TaskCategory::findOrFail($task->category);
        $subCategory = TaskCategory::findOrFail($task->sub_category);
        return view('advertiser.task_full_page', ['task' => $task , 'category' => $category, 'subCategory' => $subCategory]);
    }

    public function showCampaignDetails($taskId, Request $request)
    {
        $tab = $request->query('tab', 'pending');

        return view('advertiser.task.campaign-detail', [
            'taskId' => $taskId,
            'tab' => $tab,
        ]);
    }
}
