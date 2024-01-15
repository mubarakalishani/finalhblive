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

    public $selectedMethod;
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
                break;
            case 'perfectmoney':
                $this->minAmount = DepositMethod::where('name', 'perfectmoney')->value('min_deposit');
                break;    
            default:
                $this->minAmount = 0;
                break;               
                
        }





    }

    public function coinbaseCommerce(){
        $curl = curl_init();
        $postFilds=array(
            'name' => 'Deposit to '.env('APP_NAME'),
            'redirect_url' => url('/advertiser/deposit'),
            'cancel_url' => url('//advertiser/deposit'),
            'success_url' => url('/advertiser/deposit'),
            'local_price' => [
                'amount' => $this->amount,
                'currency' => 'USD',
            ],
            'pricing_type'=>'fixed_price',
            'metadata'=>array(
                'user_id'=> auth()->user()->unique_id,
                )
        );
        $postFilds=urldecode(http_build_query($postFilds));
        curl_setopt_array($curl, 
            array(
                CURLOPT_URL => "https://api.commerce.coinbase.com/charges",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => $postFilds,
                CURLOPT_HTTPHEADER => array(
                    "X-CC-Api-Key: ".DepositMethodSetting::where('name', 'coinbase_webhook_secret')->value('value'),
                    "X-CC-Version: 2018-03-22",
                    "content-type: multipart/form-data"
                ),
            )
        );
        $result = curl_exec($curl);
        $err = curl_error($curl);
        curl_close($curl);
        $response = json_decode($result);
        $this->paymentUrl = $response->data->hosted_url;
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
                $this->coinbaseCommerce();
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
