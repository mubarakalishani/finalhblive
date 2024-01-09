<?php

namespace App\Livewire;

use Livewire\Component;

class DynamicList extends Component
{
    public $items = [];

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
        return view('livewire.dynamic-list');
    }
}