<?php

namespace App\Livewire\Advertise;

use App\Models\Log;
use App\Models\User;
use Livewire\Component;

class MainToDepositComponent extends Component
{
    public $amount;

    public function mount(){
        $this->amount = auth()->user()->balance;
    }

    public function updatedAmount(){
        if ($this->amount > auth()->user()->balance) {
            $this->addError('minamount', 'Your selected amount $'.$this->amount.' cannot be your Main Balance $'.auth()->user()->balance);
        }
    }

    public function mainToDepositBalance(){
        if ($this->amount > auth()->user()->balance) {
            $advertiser = User::findOrFail(auth()->user()->id);
            $advertiser->decrement('balance', $this->amount);
            $advertiser->increment('deposit_balance', $this->amount);
            Log::create([
                'user_id' => auth()->user()->id,
                'description' => 'transfered '.$this->amount.' from main balance to advertising balance',
            ]);
        }
    }
    public function render()
    {
        return view('livewire.advertise.main-to-deposit-component');
    }
}
