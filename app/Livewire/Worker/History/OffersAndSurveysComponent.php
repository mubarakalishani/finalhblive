<?php

namespace App\Livewire\Worker\History;

use App\Models\OffersAndSurveysLog;
use App\Models\User;
use Livewire\Attributes\Url;
use Livewire\WithPagination;
use Livewire\Component;

class OffersAndSurveysComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';

    public $search = '';

    public $sortBy = 'updated_at';

    public $sortDir = 'DESC';

    public $perPage = 10;

    public $status = '';


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
        return view('livewire.worker.history.offers-and-surveys-component',
        [
            'histories' => OffersAndSurveysLog::search($this->search)
            ->where('user_id', auth()->user()->id)
            ->when($this->status !== '',function($query){
                $query->where('status',$this->status);
            })
            ->orderBy($this->sortBy,$this->sortDir)
            ->paginate($this->perPage)
        ]
        );
    }
}
