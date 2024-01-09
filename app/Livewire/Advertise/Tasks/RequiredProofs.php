<?php

namespace App\Livewire\Advertise\Tasks;

use Livewire\Component;

class RequiredProofs extends Component
{
    public $items = [];

    public function mount()
    {
        // Initialize the Livewire component with one item
        $this->items[] = [
            'input' => ''
        ];
    }

    public function addItem()
    {
        $this->items[] = [
            'input' => ''
        ];
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }
    public function render()
    {
        return view('livewire.advertise.tasks.required-proofs');
    }
}
