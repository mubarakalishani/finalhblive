<?php

namespace App\Livewire\Advertise\Tasks;

use Livewire\Component;
use App\Models\Task;
use App\Models\SubmittedTaskProof;
use App\Models\user;
use App\Models\AvailableRejectionReason;
use App\Models\RejectApprovalReason;
use Livewire\WithPagination;

class CampaingDetailWithApprovedProofs extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $taskId;
    public $task;
    // public $tab = 'pending';
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $perPage = 10;
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
    public function render()
    {
        return view('livewire.advertise.tasks.campaing-detail-with-approved-proofs', [
            'proofs' => $this->task->submittedProofs()
                ->where('status', 1)
                ->orderByDesc('id')
                ->paginate($this->perPage),
        ]);
    }
}
