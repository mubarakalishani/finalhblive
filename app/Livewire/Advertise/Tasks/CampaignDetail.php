<?php

namespace App\Livewire\Advertise\Tasks;

use Illuminate\Support\Facades\App;
use Livewire\Component;
use App\Models\Task;
use App\Models\SubmittedTaskProof;
use App\Models\user;
use App\Models\AvailableRejectionReason;
use App\Models\Log;
use App\Models\RejectApprovalReason;
use App\Models\Setting;
use Livewire\WithPagination;
class CampaignDetail extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $taskId;
    public $task;
    // public $tab = 'pending';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $batchSelectedProofs = [];
    public $selectedMultiple;
    public $maxBudget;
    public $hourlyBudget;
    public $dailyBudget;
    public $weeklyBudget;
    public $submissionPerHour;
    public $submissionPerDay;
    public $submissionPerWeek;

    public $availableRejectionReasons;
    public $ValidationError = '';
    public $reasonExplained;
    public $reasonSelected;
    public $proofId;
    public $rejectOrRevision;

    protected $rules = [
        'reasonExplained' => 'required|min:20',
        'reasonSelected' => 'required'
    ];
    protected $listeners = [ 'updateModalContent' => 'setModalContent'];
    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $this->task = Task::where('id', $this->taskId)->where('employer_id', auth()->user()->id)->first();
        $this->availableRejectionReasons = AvailableRejectionReason::all();
        $this->maxBudget = $this->task->max_budget;
        $this->hourlyBudget = $this->task->hourly_budget;
        $this->dailyBudget = $this->task->daily_budget;
        $this->weeklyBudget = $this->task->weekly_budget;
        $this->submissionPerHour = $this->task->submission_per_hour;
        $this->submissionPerDay = $this->task->submission_per_day;
        $this->submissionPerWeek    = $this->task->submission_per_week;
    }

    public function approve($proofId , $tab)
    {
        $submittedTaskProof = SubmittedTaskProof::find($proofId);
        $amount = $submittedTaskProof->amount;
        $workerId = $submittedTaskProof->worker_id;
        $worker = User::find($workerId);
        $advertiseId = auth()->user()->id;
        $advertiser = User::find($advertiseId);
        //check if the proof was rejected/revisionAsked and being setting approved, if so, advertisers balance must be above the reward to be added to the user as we have to cut it
        if($submittedTaskProof->status !=0 ){
            if ($advertiser->deposit_balance < $amount) {
                return back()->with('error', 'your have insufficient funds, please top up first');
            }
            $advertiser->deductAdvertiserBalance($amount);
        }
        $worker->addWorkerBalance($amount);

        $submittedTaskProof->update([ 'status' => 1 ]);

        if ($worker->upline !=0 && Setting::where('name', 'tasks_referral_status')->value('value') == 'enabled') {
            $upline = User::find($worker->upline);
            $uplineCommission = ($amount/100) * Setting::where('name', 'tasks_referral_commission')->value('value');
            $upline->addWorkerBalance($uplineCommission);
            $upline->increment('earned_from_referrals', $uplineCommission);
            Log::create([
                'user_id' => $worker->upline,
                'description' => 'received Referral commission from '.$worker->username.' for completing a task'
            ]);
        }

        session()->flash('message', 'Proof Approve Successfully!');
    }

    public function reject($proofId)
    {
        // dd($proofId);
        $SubmittedTaskProof = SubmittedTaskProof::find($proofId);
        $amount = $SubmittedTaskProof->amount;
        $workerId = $SubmittedTaskProof->worker_id;
        $worker = User::find($workerId);
        $advertiseId = auth()->user()->id;
        $advertiser = User::find($advertiseId);
        $advertiser->addAdvertiserBalance($amount); //adding the amount back to the advertiser's balance

        $SubmittedTaskProof->update([ 'status' => 2 ]);

        session()->flash('message', 'Proof Rejected Successfully!');
    }

    public function askForRevision($proofId){
        $submittedTaskProof = SubmittedTaskProof::find($proofId);
        $amount = $submittedTaskProof->amount;
        $advertiseId = auth()->user()->id;
        $advertiser = User::find($advertiseId);
        //check if the proof was rejected/revisionAsked and being setting approved, if so, advertisers balance must be above the reward to be added to the user as we have to cut it
        if($submittedTaskProof->status !=0 ){
            if ($advertiser->deposit_balance < $amount) {
                return back()->with('error', 'your have insufficient funds, please top up first');
            }
            $advertiser->deductAdvertiserBalance($amount);
        }

        $submittedTaskProof->update([ 'status' => 3 ]);

        session()->flash('message', 'Proof asked for revision Successfully!');
    }

    public function approveAllSelected(){
        foreach ($this->batchSelectedProofs as $proofId) {
            $submittedTaskProof = SubmittedTaskProof::find($proofId);
            $amount = $submittedTaskProof->amount;
            $workerId = $submittedTaskProof->worker_id;
            $worker = User::find($workerId);
            $advertiseId = auth()->user()->id;
            $advertiser = User::find($advertiseId);
            //check if the proof was rejected/revisionAsked and being setting approved, if so, advertisers balance must be above the reward to be added to the user as we have to cut it
            if($submittedTaskProof->status !=0 ){
                if ($advertiser->deposit_balance < $amount) {
                    return back()->with('error', 'your have insufficient funds, please top up first');
                }
                $advertiser->deductAdvertiserBalance($amount);
            }
            $worker->addWorkerBalance($amount);
            
            $submittedTaskProof->update([ 'status' => 1 ]);
            if ($worker->upline !=0 && Setting::where('name', 'tasks_referral_status')->value('value') == 'enabled') {
                $upline = User::find($worker->upline);
                $uplineCommission = ($amount/100) * Setting::where('name', 'tasks_referral_commission')->value('value');
                $upline->addWorkerBalance($uplineCommission);
                $upline->increment('earned_from_referrals', $uplineCommission);
                Log::create([
                    'user_id' => $worker->upline,
                    'description' => 'received Referral commission from '.$worker->username.' for completing a task'
                ]);
            }
        }
        session()->flash('message', 'all selected Tasks has been Approved');
        $this->batchSelectedProofs = [];
        $this->selectedMultiple = 'no';
    }

    public function updatedBatchSelectedProofs() {
        if ( sizeOf($this->batchSelectedProofs) > 0 ) {
            $this->selectedMultiple = 'yes';
        }else{
            $this->selectedMultiple = 'no';
        }
        // dd($this->batchSelectedProofs);
    }

    public function updatedMaxBudget(){
        $this->task->update( ['max_budget' => $this->maxBudget] );
        session()->flash('quickedit', 'Maximum Max Spend Cap Updated Successfully.');
    }

    public function updatedHourlyBudget(){
        $this->task->update( ['hourly_budget' => $this->hourlyBudget] );
        session()->flash('quickedit', 'Hourly Max Spend Cap Updated Successfully.');
    }

    public function updatedDailyBudget(){
        $this->task->update( ['daily_budget' => $this->dailyBudget] );
        session()->flash('quickedit', 'Daily Max Spend Cap Updated Successfully.');
    }

    public function updatedWeeklyBudget(){
        $this->task->update( ['weekly_budget' => $this->weeklyBudget] );
        session()->flash('quickedit', 'Weekly Max Spend Cap Updated Successfully.');
    }

    public function updatedSubmissionPerHour(){
        $this->task->update( ['submission_per_hour' => $this->submissionPerHour] );
        session()->flash('quickedit', 'Weekly Max Spend Cap Updated Successfully.');
    }

    public function updatedSubmissionPerDay(){
        $this->task->update( ['submission_per_day' => $this->submissionPerDay] );
        session()->flash('quickedit', 'Daily Max Spend Cap Updated Successfully.');
    }

    public function updatedSubmissionPerWeek(){
        $this->task->update( ['submission_per_hour' => $this->submissionPerWeek] );
        session()->flash('quickedit', 'Weekly Max Submission Cap Updated Successfully.');
    }

    public function updatedReasonExplained(){
        $this->validate();
    }

    public function setModalContent($rejectOrRevision, $proofId)
    {
        $this->rejectOrRevision = $rejectOrRevision;
        $this->proofId = $proofId;
    }

    public function submitEmployerComment()
    {
    
        $this->validate();
        RejectApprovalReason::create([
            'submitted_proof_id' => $this->proofId,
            'selected_reason' => $this->reasonSelected,
            'employer_comment' => $this->reasonExplained
        ]);
        
        if ($this->rejectOrRevision == 'rejection') {
            $this->reject($this->proofId);
        }
        elseif ($this->rejectOrRevision == 'revision') {
            $this->askForRevision($this->proofId);
        }
        
    }

    // public function updateTab($tab){
    //     $this->tab = $tab;
    //     $this->task = Task::where('id', $this->taskId)->where('employer_id', auth()->user()->id)->first();
    // }

    public function render()
    {
        return view('livewire.advertise.tasks.campaign-detail');
    }
}
