<?php

namespace App\Http\Controllers\Cronjobs;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\OffersAndSurveysLog;
use App\Models\Statistic;
use App\Models\SubmittedTaskProof;
use App\Models\Task;
use App\Models\TaskDispute;
use App\Models\User;
use Illuminate\Http\Request;

class EveryDayController extends Controller
{
    public function runCronJob(){
        $this->resetStats();
        $this->resolveResubmitExhaustTasks();
        $this->updateTasks();
        $this->creditDisputesNotResponded();
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


    protected function resolveRejectedProofs(){
        //get all the proofs for whose the resubmission allowed time is passed and mark them as resubmit time exhausted
        $rejectedDisputeTimeExhaustedProofs = SubmittedTaskProof::where(function ($query) {
            $query->whereRaw('NOW() > DATE_ADD(updated_at, INTERVAL 2 DAY)');
        })->where('status', 2)->get();
        foreach ($rejectedDisputeTimeExhaustedProofs as $rejectedDisputeTimeExhaustedproof) {
            $task= Task::find($rejectedDisputeTimeExhaustedproof->task_id);
            $employer = User::find($task->employer_id);
            $rejectedDisputeTimeExhaustedproof->update([
                'status' => 8,
            ]);
            $employer->increment('deposit_balance', $rejectedDisputeTimeExhaustedproof->amount);
            Log::create([
                'user_id' => $task->employer_id,
                'description' => 'rejected permanently and added blc back as dispute time passed p#'.$rejectedDisputeTimeExhaustedproof->id.' for task # '. $rejectedDisputeTimeExhaustedproof->task_id,
            ]);
        }
    }


    protected function resolveAppealTimeExhaustedProofs(){
        //get all the proofs for whose the resubmission allowed time is passed and mark them as resubmit time exhausted
        $appealTimeExhaustedProofs = SubmittedTaskProof::where(function ($query) {
            $query->where('updated_at', '<=', now()->subHours(30));
        })->where('status', 6)->get();
        foreach ($appealTimeExhaustedProofs as $appealTimeExhaustedProof) {
            $task= Task::find($appealTimeExhaustedProof->task_id);
            $employer = User::find($task->employer_id);
            $appealTimeExhaustedProof->update([
                'status' => 9,
            ]);
            $employer->increment('deposit_balance', $appealTimeExhaustedProof->amount);
            Log::create([
                'user_id' => $task->employer_id,
                'description' => 'reject permanently and add blc back as appeal allowed time passed p#'.$appealTimeExhaustedProof->id.' for task # '. $appealTimeExhaustedProof->task_id,
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
            $statistics = Statistic::latest()->firstOrCreate([]);
            $statistics->increment('tasks_total_earned', $employerReviewPassedProof->amount);
            $statistics->increment('tasks_today_earned', $employerReviewPassedProof->amount);
            $statistics->increment('tasks_this_month', $employerReviewPassedProof->amount);
            $worker->increment('balance', $employerReviewPassedProof->amount);
            $worker->increment('earned_from_tasks', $employerReviewPassedProof->amount);
            $worker->increment('total_earned', $employerReviewPassedProof->amount);
            $worker->increment('total_tasks_completed');

            $employerReviewPassedProof->update([
                'status' => 1
            ]);
        }
    }

    protected function creditDisputesNotResponded(){
        $expiredDisputes = TaskDispute::where(function ($query) {
            $query->whereRaw('NOW() > DATE_ADD(updated_at, INTERVAL 3 DAY)');
        })->where('status', 0)->get();

        foreach ($expiredDisputes as $expiredDispute) {
            $employer = User::find( $expiredDispute->employer_id );
            $worker = User::find( $expiredDispute->worker_id );
            $proof = SubmittedTaskProof::find( $expiredDispute->proof_id );

            $employer->decrement('deposit_balance', abs($expiredDispute->proof->amount));
            $worker->increment('balance', $expiredDispute->proof->amount);
            $worker->increment('earned_from_tasks', $expiredDispute->proof->amount);
            $worker->increment('total_earned', $expiredDispute->proof->amount);
            $worker->increment('total_tasks_completed');
            $proof->update([
                'status' => 1
            ]);

            //update statistics
            $statistics = Statistic::latest()->firstOrCreate([]);
            $statistics->increment('tasks_total_earned', $expiredDispute->proof->amount);
            $statistics->increment('tasks_today_earned', $expiredDispute->proof->amount);
            $statistics->increment('tasks_this_month', $expiredDispute->proof->amount);

            $expiredDispute->update([
                'status' => 1
            ]);

            Log::create([
                'user_id' => $expiredDispute->employer_id,
                'description' => 'dispute expired to respond to added '.$expiredDispute->proof->amount.' to user '.$expiredDispute->worker_id.' for task#'.$expiredDispute->task_id
            ]);
        }
    }

    protected function resetStats(){
        $firstDayOfLastMonth = now()->subMonth()->startOfMonth();
        $lastDayOfLastMonth = now()->subMonth()->endOfMonth();
        $statistic = Statistic::latest()->firstOrCreate([]);
        $statistic->update([
            'tasks_today_earned' => 0,
            'offers_today_earned' => 0,
            'shortlinks_today_earned' => 0,
            'ptc_today_earned' => 0,
            'faucet_today_earned' => 0,
            'offers_total_earned' => OffersAndSurveysLog::whereIn('status', [0,1])->sum('payout'),
            'offers_this_month' => OffersAndSurveysLog::whereIn('status', [1,0])
            ->whereYear('created_at', '=', now()->year)
            ->whereMonth('created_at', '=', now()->month)
            ->sum('payout'),
            'offers_last_month' => OffersAndSurveysLog::whereIn('status', [0,1])->whereBetween('created_at', [$firstDayOfLastMonth, $lastDayOfLastMonth])->sum('payout'),
        ]);
        
    }
}
