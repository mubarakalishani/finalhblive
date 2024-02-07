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
        $histories = OffersAndSurveysLog::search($this->search)
        ->where('user_id', auth()->user()->id)
        ->when($this->status !== '',function($query){
            $query->where('status',$this->status);
        })
        ->orderBy($this->sortBy,$this->sortDir)
        ->paginate($this->perPage);

        foreach ($histories as $history) {
            $expirationTime = $history->created_at->addMinutes($history->hold_time);
            if (now()->greaterThanOrEqualTo($expirationTime || $history->status == 0)) {
                $remark = 'completed';
            }
            else{
                $remainingTime = $expirationTime->diffForHumans(now(), [
                    'parts' => 2, // Show only two parts (e.g., "2 days 3 hours")
                    'short' => true, // Use short format (e.g., "2d 3h")
                ]);
                $remainingTime = str_replace([' ago', ' after'], '', $remainingTime);
                $remark = 'releasing after '.$remainingTime;
            }

            $history->remark = $remark;
        }
        return view('livewire.worker.history.offers-and-surveys-component',
        [
            'histories' => $histories
        ]
        );
    }
}
