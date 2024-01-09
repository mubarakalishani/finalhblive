<?php

namespace App\Livewire;
use Livewire\Component;

class LiveValidationExample extends Component
{
    public $name;

    protected $rules = [
        'name' => 'required|min:3',
    ];

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
        
    public function render()
    {
        return view('livewire.live-validation-example');
    }
}
