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
    public function resolveResubmitExhaustTasks(){
        //get all the proofs for whose the resubmission allowed time is passed and mark them as resubmit time exhausted
        $resubmitExhaustedProofs = SubmittedTaskProof::where('status', 7)->get();
        foreach ($resubmitExhaustedProofs as $resubmitExhaustedProof) {
            $task= Task::find($resubmitExhaustedProof->task_id);
            $employer = User::find($task->employer_id);
            // $resubmitExhaustedProof->update([
            //     'status' => 7,
            // ]);
            $employer->increment('deposit_balance', $resubmitExhaustedProof->amount);
            Log::create([
                'user_id' => $task->employer_id,
                'description' => 'resubmit time passed amount for proof id '.$resubmitExhaustedProof->id.' for task # '. $resubmitExhaustedProof->task_id,
            ]);
        }
    }
}
