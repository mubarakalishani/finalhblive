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

    public $payeerStatus;
    public $airtmStatus;
    public $faucetpayStatus;
    public $coinbaseCommerceStatus;
    public $paypalStatus;
    public $perfectmoneyStatus;

    public $selectedMethod = "coinbasecommerce";
    public $amount = 0;
    public $minAmount = 0;
    public $paymentUrl;

    protected $rules = [

    ];

    public function updatedSelectedMethod(){
        switch($this->selectedMethod)
        {
            case 'airtm':
                $this->minAmount = DepositMethod::where('name', 'airtm')->value('min_deposit');
                break;
            case 'payeer':
                $this->minAmount = DepositMethod::where('name', 'payeer')->value('min_deposit');
                break;
            case 'faucetpay':
                $this->minAmount = DepositMethod::where('name', 'faucetpay')->value('min_deposit');
                $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');
                $this->paymentUrl = 'https://faucetpay.io/merchant/webscr?currency2=""&merchant_username='.$faucetpayUsername.'&item_description=Deposit+to+Handbucks&currency1=USDT&amount1='.$this->amount.'&custom='.auth()->user()->unique_user_id.'&callback_url=https://handbucks.com/faucetpay/callback&success_url=https://handbucks.com/advertiser/deposit&cancel_url=https://handbucks.com/advertiser/deposit&completed=0';
                break;
            case 'paypal':
                $this->minAmount = DepositMethod::where('name', 'paypal')->value('min_deposit');
                break;
            case 'coinbasecommerce':
                $this->minAmount = DepositMethod::where('name', 'coinbasecommerce')->value('min_deposit');
                $this->paymentUrl = url('/pay/coinbase?amount='.$this->amount);
                break;
            case 'perfectmoney':
                $this->minAmount = DepositMethod::where('name', 'perfectmoney')->value('min_deposit');
                break;    
            default:
                $this->minAmount = 0;
                break;               
                
        }





    }

    public function updatedAmount(){
        switch($this->selectedMethod)
        {
            case 'airtm':
                $this->amount < DepositMethod::where('name', 'airtm')->value('min_deposit') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount);
                break;
            case 'payeer':
                $this->amount < DepositMethod::where('name', 'payeer')->value('min_deposit') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                break;
            case 'faucetpay':
                $this->amount < DepositMethod::where('name', 'faucetpay')->value('min_deposit') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');
                $this->paymentUrl = 'https://faucetpay.io/merchant/webscr?currency2=""&merchant_username='.$faucetpayUsername.'&item_description=Deposit+to+Handbucks&currency1=USDT&amount1='.$this->amount.'&custom='.auth()->user()->unique_user_id.'&callback_url=https://handbucks.com/faucetpay/callback&success_url=https://handbucks.com/advertiser/deposit&cancel_url=https://handbucks.com/advertiser/deposit&completed=0';
                break;
            case 'paypal':
                $this->amount < DepositMethod::where('name', 'paypal')->value('min_deposit') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                break;
            case 'coinbasecommerce':
                $this->amount < DepositMethod::where('name', 'coinbasecommerce')->value('min_deposit') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                $this->paymentUrl = url('/pay/coinbase?amount='.$this->amount);
                break;
            case 'perfectmoney':
                $this->amount < DepositMethod::where('name', 'perfectmoney')->value('min_deposit') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                break;    
            default:
                $this->minAmount = 0;
                break;               
                
        }
    }


    public function pay(){
        
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
