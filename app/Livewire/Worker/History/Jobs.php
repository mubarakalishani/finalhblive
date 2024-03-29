<?php

namespace App\Livewire\Worker\History;

use App\Models\User;
use App\Models\SubmittedTaskProof;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;


class Jobs extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    #[Url(as: 'search')]
    public $search = '';
    #[Url(as: 'sort')]
    public $sortDir = 'DESC';
    #[Url(as: 'perPage')]
    public $perPage = 10;
    #[Url(as: 'status')]
    public $status = '';
    public $sortBy = 'updated_at';


    public function updatedSearch(){
        $this->resetPage();
    }

    public function setSortBy($sortByField){

        if($this->sortBy === $sortByField){
            $this->sortDir = ($this->sortDir == "ASC") ? 'DESC' : "ASC";
            return;
        }

        $this->sortBy = $sortByField;
        $this->sortDir = 'DESC';
    }

    public function render()
    {
        return view('livewire.worker.history.jobs',
        [
            'proofs' => SubmittedTaskProof::search($this->search)
            ->where('worker_id', auth()->user()->id)
            ->when($this->status !== '',function($query){
                $query->where('status',$this->status);
            })
            ->with('task')
            ->orderBy($this->sortBy,$this->sortDir)
            ->paginate($this->perPage)
        ]
        );
    }
}
