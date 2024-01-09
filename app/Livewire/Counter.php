<?php

namespace App\Livewire;

use Livewire\Component;

class Counter extends Component
{
    public $count = 1;
    public $fields = [0];

    public function increment()
    {
        $this->count++;
        $this->fields[] = $this->count;
    }

    public function decrement($index)
    {
        if (count($this->fields) > 1) {
            unset($this->fields[$index]);
            $this->fields = array_values($this->fields); // Re-index the array
        }
    }
    

    public function render()
    {
        return view('livewire.counter');
    }
}
