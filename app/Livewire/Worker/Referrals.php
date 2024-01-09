<?php

namespace App\Livewire\Worker;

use Livewire\Component;
use App\Models\User;
use Livewire\WithPagination;

class Referrals extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $sortBy = 'created_at';

    public $sortDir = 'DESC';

    public $perPage = 10;


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
        return view('livewire.worker.referrals',
        [
            'referrals' => User::search($this->search)
            ->where('upline', auth()->user()->id)
            ->orderBy($this->sortBy,$this->sortDir)
            ->paginate($this->perPage)
        ]
        );

    }
}