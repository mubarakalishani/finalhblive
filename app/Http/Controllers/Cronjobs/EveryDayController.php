<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\SubmittedTaskProof;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;

class EveryDayController extends Controller
{
    public function runCronJob(){
        $this->resolveResubmitExhaustTasks();
        $this->updateTasks();
    }

    protected function resolveResubmitExhaustTasks(){
        //get all the proofs for whose the resubmission allowed time is passed and mark them as resubmit time exhausted
        $resubmitExhaustedProofs = SubmittedTaskProof::where(function ($query) {
            $query->whereRaw('NOW() > DATE_ADD(updated_at, INTERVAL 3 DAY)');
        })->where('status', 3)->get();
        foreach ($resubmitExhaustedProofs as $resubmitExhaustedProof) {
            $task= Task::find($resubmitExhaustedProof->task_id);
            $employer = User::find($task->employer_id);
            $resubmitExhaustedProof->update([
                'status' => 7,
            ]);
            $employer->increment('deposit_balance', $resubmitExhaustedProof->amount);
            Log::create([
                'user_id' => $task->employer_id,
                'description' => 'resubmit time passed amount for proof id '.$resubmitExhaustedProof->id.' for task # '. $resubmitExhaustedProof->task_id,
            ]);
        }
    }

    protected function updateTasks(){
        //now get all the pending proofs that are passed the employer allowed review time
        $employerReviewPassedProofs = SubmittedTaskProof::whereHas('task', function ($query) {
            $query->whereRaw('DATEDIFF(CURRENT_DATE, submitted_task_proofs.updated_at) > tasks.rating_time');
        })
        ->where('status', 0)
        ->get();

        foreach ($employerReviewPassedProofs as $employerReviewPassedProof) {
            $worker = User::find($employerReviewPassedProof->worker_id);
            $worker->increment('balance', $employerReviewPassedProof->amount);
            $worker->increment('earned_from_tasks', $employerReviewPassedProof->amount);
            $worker->increment('total_earned', $employerReviewPassedProof->amount);
            $worker->increment('total_tasks_completed');

            $employerReviewPassedProof->update([
                'status' => 1
            ]);
        }
    }
}
