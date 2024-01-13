<?php

namespace App\Livewire\Worker\Profile;

use App\Models\PayoutGateway;
use App\Models\WalletAddress;
use Livewire\Component;

class PaymentMethods extends Component
{
    public $wallet;
    public $placeholder;
    public $selectedGateway;
    public $gateway;
    public $addButtonClicked = 0;
    public $editId = 0;


    protected $rules = [
        'selectedGateway' => 'required',
        'wallet' => 'required',
    ];

    public function toggleAddButton(){
        if ($this->addButtonClicked == 1) {
            $this->addButtonClicked =0;
        }else{
            $this->addButtonClicked =1;
        }
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
                case 'Perfect Money':
                    $this->placeholder = 'Perfect Money UID';
                    break; 
                case 'Payeer':
                    $this->placeholder = 'Your Payeer Account ID e.g, Pxxxxxx';
                    break;
                case 'USDT Polygon':
                    $this->placeholder = 'USDT Address on polygon e.g, 0x.......';
                    break;
                case 'Binance Pay ID':
                    $this->placeholder = 'Your Binance Pay UID';
                    break;
                case 'USDT BEP20':
                    $this->placeholder = 'USDT Address on Binance Chain e.g, 0x.......';
                    break; 
                case 'Payoneer':
                    $this->placeholder = 'Payoneer verified email address';
                    break;        
                default:
                    $this->placeholder = $this->placeholder;
                    break;
            }
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



    public function editRecord($id){
        $this->editId = $id;
        $this->wallet = WalletAddress::where('id', $id)->value('address');
    }


    public function deleteRecord($id){
        $walletRecord = WalletAddress::find($id);
        if ($walletRecord) {
            if($walletRecord->user_id == auth()->user()->id){
                $walletRecord->delete();
            }
        }
    }


    public function updatedWallet(){
        if ($this->editId != 0) {
            $walletRecord = WalletAddress::find($this->editId);
            if ($walletRecord->user_id == auth()->user()->id) {
                $walletRecord->update([
                    'address' => $this->wallet,
                ]);
            }
            $this->editId = 0;
            session()->flash('updateWalletMessage', 'Updated Successfully.');
        }
    }


    public function submit(){
        $this->validate();
        switch ($this->gateway->name) {
            case 'USDT Polygon':
                $this->validateEthereumAddress();
                break;
            case 'USDT BEP20':
                $this->validateEthereumAddress();
                break;
            case 'FaucetPay':
                $this->validateFaucetPayAddress();
                break;       
        }
        if ($this->getErrorBag()->any()) {
            // Handle errors or return early
            return;
        }

        WalletAddress::create([
            'user_id' => auth()->user()->id,
            'payment_geteway_id' => $this->selectedGateway,
            'address' => $this->wallet,
        ]);
        $this->reset();
        session()->flash('successMessage', 'The Wallet Address has been saved Successfully.');
    }

    public function render()
    {
        $wallets = WalletAddress::where('user_id', auth()->user()->id)->get();
        $methods = PayoutGateway::where('status', 1)->get();
        return view('livewire.worker.profile.payment-methods', [
            'wallets' => $wallets, 
            'methods' => $methods

        ]);
    }
}
