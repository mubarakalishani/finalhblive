<?php

namespace App\Livewire\Advertise;

use Livewire\Component;
use Illuminate\Support\Facades\Response;
use App\Models\DepositMethodSetting;
use Livewire\WithPagination;
use App\Models\Deposit;
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

    public $selectedMethod = 'faucetpay';
    public $amount = 0;
    public $minAmount = 0;
    public $paymentUrl;

    protected $rules = [

    ];
    public function mount()
    {
        $this->minAmount = DepositMethodSetting::where('name', 'faucetpay_min_deposit')->value('value');
        $this->payeerStatus = DepositMethodSetting::where('name', 'payeer_status')->value('value');
        $this->paypalStatus = DepositMethodSetting::where('name', 'paypal_status')->value('value');
        $this->airtmStatus = DepositMethodSetting::where('name', 'airtm_status')->value('value');
        $this->faucetpayStatus = DepositMethodSetting::where('name', 'faucetpay_status')->value('value');
        $this->coinbaseCommerceStatus = DepositMethodSetting::where('name', 'coinbasecommerce_status')->value('value');
        $this->perfectmoneyStatus = DepositMethodSetting::where('name', 'perfectmoney_status')->value('value');
    }

    public function updatedSelectedMethod(){
        switch($this->selectedMethod)
        {
            case 'airtm':
                $this->minAmount = DepositMethodSetting::where('name', 'airtm_min_deposit')->value('value');
                break;
            case 'payeer':
                $this->minAmount = DepositMethodSetting::where('name', 'payeer_min_deposit')->value('value');
                break;
            case 'faucetpay':
                $this->minAmount = DepositMethodSetting::where('name', 'faucetpay_min_deposit')->value('value');
                $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');
                $this->paymentUrl = 'https://faucetpay.io/merchant/webscr?currency2=""&merchant_username='.$faucetpayUsername.'&item_description=Deposit+to+Handbucks&currency1=USDT&amount1='.$this->amount.'&custom='.auth()->user()->unique_user_id.'&callback_url=https://handbucks.com/faucetpay/callback&success_url=https://handbucks.com/advertiser/deposit&cancel_url=https://handbucks.com/advertiser/deposit&completed=0';
                break;
            case 'paypal':
                $this->minAmount = DepositMethodSetting::where('name', 'paypal_min_deposit')->value('value');
                break;
            case 'coinbasecommerce':
                $this->minAmount = DepositMethodSetting::where('name', 'coinbasecommerce_min_deposit')->value('value');
                break;
            case 'perfectmoney':
                $this->minAmount = DepositMethodSetting::where('name', 'perfectmoney_min_deposit')->value('value');
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
                $this->amount < DepositMethodSetting::where('name', 'airtm_min_deposit')->value('value') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount);
                break;
            case 'payeer':
                $this->amount < DepositMethodSetting::where('name', 'payeer_min_deposit')->value('value') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                break;
            case 'faucetpay':
                $this->amount < DepositMethodSetting::where('name', 'faucetpay_min_deposit')->value('value') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                $faucetpayUsername = DepositMethodSetting::where('name', 'faucetpay_merchant_username')->value('value');
                $this->paymentUrl = 'https://faucetpay.io/merchant/webscr?currency2=""&merchant_username='.$faucetpayUsername.'&item_description=Deposit+to+Handbucks&currency1=USDT&amount1='.$this->amount.'&custom='.auth()->user()->unique_user_id.'&callback_url=https://handbucks.com/faucetpay/callback&success_url=https://handbucks.com/advertiser/deposit&cancel_url=https://handbucks.com/advertiser/deposit&completed=0';
                break;
            case 'paypal':
                $this->amount < DepositMethodSetting::where('name', 'paypal_min_deposit')->value('value') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                break;
            case 'coinbasecommerce':
                $this->amount < DepositMethodSetting::where('name', 'coinbasecommerce_min_deposit')->value('value') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
                break;
            case 'perfectmoney':
                $this->amount < DepositMethodSetting::where('name', 'perfectmoney_min_deposit')->value('value') ? $this->addError('minamount', 'Min deposit amount for '.$this->selectedMethod.' must be at least $'.$this->minAmount) : $this->amount = $this->amount;
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
        $depositLogs = Deposit::where('user_id', auth()->user()->id)->orderBy('updated_at', 'desc')->paginate($this->perPage);
        return view('livewire.advertise.deposit-component', [
            'depositLogs' => $depositLogs
        ]);
    }
}
