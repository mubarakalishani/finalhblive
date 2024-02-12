<?php

namespace App\Livewire\Worker;

use App\Models\PayoutGateway;
use App\Models\User;
use App\Models\WithdrawalHistory;
use Livewire\Component;
use Livewire\WithPagination;

class WithdrawComponent extends Component
{
    use WithPagination;
    protected $paginationTheme = 'bootstrap';
    public $sortField = 'updated_at';
    public $sortDir = 'DESC';
    public $perPage = 10;
    public $amount = 0.00; //the no fee amount that user inputs
    public $selectedGateway;
    public $placeholder;
    public $gateway;
    public $wallet;
    public $description = '';
    public $processingFee;

    protected $rules = [
        'selectedGateway' => 'required',
        'wallet' => 'required',
        'amount' => 'required|numeric|min:0|max:1000',
    ];

    public function updateAmount($amount)
    {
        switch ($amount) {
            case 50:
                $this->amount = (50 * auth()->user()->balance) / 100;
                break;
            case 100:
                $this->amount =  auth()->user()->balance;
                break;    
        }
        if($this->amount > auth()->user()->balance){
            $this->addError('errAmount', 'the amount is more than Your available balance '.auth()->user()->balance);
        }
        elseif($this->amount < $this->gateway->min_payout) {
            $this->addError('errAmount', 'The minimum payout amount for '.$this->gateway->name.' is '.$this->gateway->min_payout);
        }
        $this->processingFee = $this->gateway->fixed_fee + ($this->gateway->fee_percentage * $this->amount) / 100 ;
    }

    public function updatedSelectedGateway(){
        
        $this->gateway = PayoutGateway::find($this->selectedGateway);
        if($this->gateway)
        {
            switch ($this->gateway->name) {
                case 'Airtm':
                    $this->placeholder = 'Your Airtm email';
                    break;
                case 'FaucetPay':
                    $this->placeholder = 'Your FaucetPay USDT Address or username or email';
                    break;
                case 'Faucet Pay':
                        $this->placeholder = 'Your FaucetPay USDT Address or username or email';
                        break;
                case 'Perfect Money':
                    $this->placeholder = 'Perfect Money UID';
                    break;
                case 'Perfectmoney':
                    $this->placeholder = 'Perfect Money UID';
                    break; 
                case 'Payeer':
                    $this->placeholder = 'Your Payeer Account ID e.g, Pxxxxxx';
                    break;
                case 'USDT Polygon':
                    $this->placeholder = 'USDT Address on polygon e.g, 0x.......';
                    break;
                case 'USDT':
                    $this->placeholder = 'USDT Address on polygon e.g, 0x.......';
                    break;
                case 'Binance Pay ID':
                    $this->placeholder = 'Your Binance Pay UID';
                    break;
                case 'Binance Pay':
                    $this->placeholder = 'Your Binance Pay UID';
                    break;
                case 'USDT BEP20':
                    $this->placeholder = 'USDT Address on Binance Chain e.g, 0x.......';
                    break; 
                case 'Payoneer':
                    $this->placeholder = 'Payoneer verified email address';
                    break;        
                default:
                    $this->placeholder = 'Your wallet address';
                    break;
            }
        }
        
    }


    public function updatedWallet()
    {
        $this->resetErrorBag('wallet');
    }


    public function updatedAmount()
    {
        if($this->amount > auth()->user()->balance){
            $this->addError('errAmount', 'the amount is more than Your available balance '.auth()->user()->balance);
        }
        elseif($this->amount < $this->gateway->min_payout) {
            $this->addError('errAmount', 'The minimum payout amount for '.$this->gateway->name.' is '.$this->gateway->min_payout);
        }
        else{
            $this->processingFee = $this->gateway->fixed_fee + ($this->gateway->fee_percentage * $this->amount) / 100 ;
        }
    }

    public function validateEthereumAddress()
    {
        $this->validate([
            'wallet' => ['required', 'string', 'regex:/^(0x)?[0-9a-fA-F]{40}$/'],
            ], [
                'wallet.required' => 'The Wallet address is required.',
                'wallet.string' => 'The Address is not in a valid format.',
                'wallet.regex' => 'The address is not in a valid address. it must be a valid evm address like this: 0xc2132D05D31c914a87C6611C10748AEb04B58e8F. check again and eliminate any extra blank spaces etc',
        ]);
    }

    public function validateFaucetPayAddress(){
        $apiEndpoint = 'https://faucetpay.io/api/v1/checkaddress';
        $apiKey = 'eba9637922c60c044faa820dec46dd872d76d56b04f1a33c38c7143a1c4ab76a';

        $walletAddress = $this->wallet;

        // Prepare the data to be sent in the request
        $data = [
            'api_key' => $apiKey,
            'address' => $walletAddress,
        ];

        // Initialize cURL session
        $ch = curl_init();

        // Set cURL options
        curl_setopt($ch, CURLOPT_URL, $apiEndpoint);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Execute cURL session
        $response = curl_exec($ch);

        // Check for cURL errors
        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        // Close cURL session
        curl_close($ch);

        // Decode the JSON response
        $result = json_decode($response, true);
        if ($result['status'] == 200) {
            
        } else {
            // Wallet address is not valid
            $this->addError('wallet', 'The submitted address does not belong to any faucetpay user, kindly check it again');
        }
    }

    public function submit(){
        $this->validate();
        switch ($this->gateway->name) {
            case 'USDT Polygon':
                $this->validateEthereumAddress();
                break;
            case 'USDT':
                $this->validateEthereumAddress();
                break;    
            case 'USDT BEP20':
                $this->validateEthereumAddress();
                break;
            case 'FaucetPay':
                $this->validateFaucetPayAddress();
                break;  
            case 'Faucet Pay':
                $this->validateFaucetPayAddress();
                break;          
        }
        if ($this->getErrorBag()->any()) {
            // Handle errors or return early
            return;
        }
        $amountAfterFee = $this->amount - ($this->gateway->fixed_fee + ($this->gateway->fee_percentage * $this->amount) / 100 );
        if ($this->amount <= auth()->user()->balance && $this->gateway->status == 1 && $this->amount >= $this->gateway->min_payout) {
            // dd('the checks passed successfully');
            WithdrawalHistory::create([
                'user_id' => auth()->user()->id,
                'method' => $this->gateway->name,
                'wallet' => $this->wallet,
                'amount_no_fee' => $this->amount,
                'amount_after_fee' => $amountAfterFee,
                'fee' => $this->amount - $amountAfterFee,
                'status' => 0,
                'description' => $this->description
            ]);
            $user = User::find(auth()->user()->id);
            $user->deductWorkerBalance($this->amount);
            $this->reset();
            session()->flash('successMessage', 'Your Withdarawal Request has been received and will be completed within next 24hrs');
        }
    }






    public function render()
    {
        $payoutGateways = PayoutGateway::where('status', 1)->get();
        $withdrawalHistories = WithdrawalHistory::where('user_id', auth()->user()->id)
        ->orderBy($this->sortField, $this->sortDir)
        ->paginate($this->perPage);
        return view('livewire.worker.withdraw-component', [
            'withdrawalHistories' => $withdrawalHistories,
            'payoutGateways' => $payoutGateways,
            'updatedAmount' => $this->amount,
            'updatedGateway' => $this->gateway
        ]);
    }
}
