<?php

namespace App\Livewire\Advertise\Tasks;

use Livewire\Component;
use App\Models\Task;
class CampaignList extends Component
{
    public $tasks;
    public $sortField = 'id';
    public $sortDirection = 'desc';
    public $search;
    public $status;

    public function mount(){
        $employerId = auth()->user()->id;
        $this->tasks = Task::where('employer_id', $employerId)
        ->orderBy($this->sortField, $this->sortDirection)
        ->get();
    }

    public function updatedSearch(){
        $employerId = auth()->user()->id;
        $this->tasks = Task::where('employer_id', $employerId)
        ->where('title', 'like', '%' . $this->search . '%')
        ->orderBy($this->sortField, $this->sortDirection)
        ->get();
    }

    public function updatedStatus(){
        $employerId = auth()->user()->id;
        if ($this->status == 10) {
            $this->tasks = Task::where('employer_id', $employerId)
            ->where('title', 'like', '%' . $this->search . '%')
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();
        }
        else{
            $this->tasks = Task::where('employer_id', $employerId)
            ->where('title', 'like', '%' . $this->search . '%')
            ->where('status', $this->status)
            ->orderBy($this->sortField, $this->sortDirection)
            ->get();
        }
    }

    public function pauseResume($taskId){
        $task = Task::find($taskId);
        if($task->status == 1){
            $task->update(['status' => 3]);
        }elseif($task->status == 3){
            $task->update(['status' => 1]);
        }
        $this->mount();
        
    }

    public function render()
    {
        return view('livewire.advertise.tasks.campaign-list');
    }
}
