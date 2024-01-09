<?php

namespace App\Livewire\Worker;
use App\Models\PtcAd;
use Livewire\Component;

class PtcIframe extends Component
{
    public $url;
    public $timeRemaining = 30;
    private $timer;
    public function mount($uniqueId)
    {
        $this->url = PtcAd::where('unique_id', $uniqueId)->value('url');
    }

    public function startTimer()
    {
        
            $this->timeRemaining--;
            if ($this->timeRemaining <= 0) {
                $this->timeRemaining = 0;
            }
            // $this->dispatch('dataUpdated', $this->timeRemaining);
    }


    public function render()
    {
        return view('livewire.worker.ptc-iframe');
    }
}
