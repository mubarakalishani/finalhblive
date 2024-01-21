<?php

namespace App\Livewire\Advertise;

use Livewire\Component;
use Illuminate\Support\Facades\Response;
use App\Models\DepositMethodSetting;
use Livewire\WithPagination;
use App\Models\Deposit;
use App\Models\DepositMethod;

class DepositComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $perPage = 5;

    public $selectedMethod = "faucetpay";
    public $amount = 0;
    public $minAmount = 0;
    public $paymentUrl;
    public $selectedGateway;      //selectedMethod is just a name of the gateway selected while this one is the model

    public function mount(){
        $this->selectedGateway = DepositMethod::where('name', 'faucetpay')->first();
    }

    public function updatedSelectedMethod(){
        $this->selectedGateway = DepositMethod::where('name', $this->selectedMethod)->first();
        switch($this->selectedGateway->name)
        {
            case 'faucetpay':
                $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');
                $this->paymentUrl = 'https://faucetpay.io/merchant/webscr?currency2=""&merchant_username='.$faucetpayUsername.'&item_description=Deposit+to+Handbucks&currency1=USDT&amount1='.$this->amount.'&custom='.auth()->user()->unique_user_id.'&callback_url=https://handbucks.com/faucetpay/callback&success_url=https://handbucks.com/advertiser/deposit&cancel_url=https://handbucks.com/advertiser/deposit&completed=0';
                break;
            case 'coinbasecommerce':
                $this->paymentUrl = url('/pay/coinbase?amount='.$this->amount);
                break;   
            default:
                $this->minAmount = 0;
                break;
        }
    }

    public function updatedAmount(){
        $this->selectedGateway = DepositMethod::where('name', $this->selectedMethod)->first();
        if ( $this->amount < $this->selectedGateway->min_deposit ) {
            $this->addError('minamount', 'Min deposit amount for '.$this->selectedGateway->name.' must be at least $'.$this->minAmount);
        }
        switch($this->selectedGateway->name)
        {
            case 'faucetpay':
                $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');
                $this->paymentUrl = 'https://faucetpay.io/merchant/webscr?currency2=""&merchant_username='.$faucetpayUsername.'&item_description=Deposit+to+Handbucks&currency1=USDT&amount1='.$this->amount.'&custom='.auth()->user()->unique_user_id.'&callback_url=https://handbucks.com/faucetpay/callback&success_url=https://handbucks.com/advertiser/deposit&cancel_url=https://handbucks.com/advertiser/deposit&completed=0';
                break;
            case 'coinbasecommerce':
                $this->paymentUrl = url('/pay/coinbase?amount='.$this->amount);
                break;   
            default:
                $this->minAmount = 0;
                break;
        }
    }

    public function render()
    {
        $depositMethods = DepositMethod::where('status', 1)->get();
        $depositLogs = Deposit::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->paginate($this->perPage);
        return view('livewire.advertise.deposit-component', [
            'depositLogs' => $depositLogs,
            'depositMethods' => $depositMethods
        ]);
    }
}
