<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Task;
use App\Models\SubmittedTaskProof;
use App\Models\User;
use App\Models\AvailableRejectionReason;
use App\Models\RejectApprovalReason;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;


class ReviewTasks extends Component
{
    use WithPagination;
    public $taskId;
    public $task;
    public $submittedProofs;
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
    public function mount($taskId)
    {
        $this->taskId = $taskId;
        $this->task = Task::findOrFail($taskId);
        $this->submittedProofs = SubmittedTaskProof::where('task_id' , $taskId)->where('status', 0)->get();
        $this->availableRejectionReasons = AvailableRejectionReason::all();
    }

    public function approveProof($proofId)
    {
        $SubmittedTaskProof = SubmittedTaskProof::find($proofId);
        $amount = $SubmittedTaskProof->value('amount');
        $workerId = $SubmittedTaskProof->value('worker_id');
        $worker = User::find($workerId);
        $advertiseId = Auth::user()->id;
        $advertiser = User::find($advertiseId);

        $advertiser->deductAdvertiserBalance($amount);
        $worker->addWorkerBalance($amount);

        $SubmittedTaskProof->update([ 'status' => 1 ]);
        $this->dispatch('refreshComponent');

        session()->flash('message', 'Proof Approve Successfully!');

    }

    public function rejectProof()
    {
        
        // $SubmittedTaskProof = SubmittedTaskProof::find($proofId);
        // $amount = $SubmittedTaskProof->value('amount');
        // $workerId = $SubmittedTaskProof->value('worker_id');
        // $worker = User::find($workerId);
        // $advertiseId = Auth::user()->id;
        // $advertiser = User::find($advertiseId);

        // $advertiser->deductAdvertiserBalance($amount);
        // $worker->addWorkerBalance($amount);

        // $SubmittedTaskProof->update([ 'status' => 1 ]);
        // $this->dispatch('refreshComponent');

        // session()->flash('message', 'Proof Approve Successfully!');

    }


    protected $listeners = ['refreshComponent', 'updateModalContent' => 'setModalContent'];
    public function refreshComponent()
    {
        // Fetch the latest data
        $this->submittedProofs = SubmittedTaskProof::where('task_id' , $this->taskId)->where('status', 0)->get();
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

        $SubmittedTaskProof = SubmittedTaskProof::find($this->proofId);
        $amount = $SubmittedTaskProof->value('amount');
        $workerId = $SubmittedTaskProof->value('worker_id');
        $worker = User::find($workerId);
        $advertiseId = Auth::user()->id;
        $advertiser = User::find($advertiseId);

        
        if ($this->rejectOrRevision == 'rejection') {
            $SubmittedTaskProof->update([ 'status' => 2 ]);  //add the locked money again to advertiser spendable fund
            $advertiser->addAdvertiserBalance($amount);

        }
        elseif ($this->rejectOrRevision == 'revision') {
            $SubmittedTaskProof->update([ 'status' => 3 ]);
        }
        
        $this->dispatch('refreshComponent');
        
    }

    public function updated($property)
    {
        $this->validate();
        // dump('updated '.$property);
    }

    public function render()
    {
        return view('livewire.review-tasks');
    }
}
