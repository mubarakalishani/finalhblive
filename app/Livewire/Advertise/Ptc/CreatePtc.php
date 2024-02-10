<?php

namespace App\Livewire\Advertise\Ptc;

use Livewire\Component;

use App\Models\AvailableCountry;
use App\Models\ArabCountry;
use App\Models\PtcAdPackage;
use App\Models\PtcAd;
use App\Models\User;
use WisdomDiala\Countrypkg\Models\Country;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class CreatePtc extends Component
{
    public $title;
    public $description;
    public $url;
    public $adRevisionInterval;
    public $viewsAmount;
    protected $ptcAd;
    public $ptcAdPackage;
    public $packagePrice;
    public $type;
    public $totalCost;
    protected $countryCount;
    public $includedCountries = [];
    public $excludedCountries = [];
    public $availableCountries = [];
    public $arabCountries = [];
    protected function rules(){
        return [
            'title' => 'required|max:15',
            'description' => 'required|max:100|min:10',
            'adRevisionInterval' => 'required',
            'url' => 'url:http,https',
            'viewsAmount' => 'integer|min:' . PtcAdPackage::where('reward_per_view', $this->packagePrice)->value('minimum_views'),
        ];
    } 

    public function mount()
    {
        $this->ptcAdPackage = PtcAdPackage::all();
        $this->packagePrice = PtcAdPackage::orderBy('reward_per_view')->value('reward_per_view');
        $this->viewsAmount = PtcAdPackage::orderBy('reward_per_view')->value('minimum_views');
        $this->totalCost = $this->packagePrice * $this->viewsAmount;
        $this->type = 0;
        $this->arabCountries = ArabCountry::whereNotNull('country_name')->get();
        $countries = AvailableCountry::pluck('country_name')->toArray();
        $this->includedCountries = $countries;
        $this->availableCountries = array_diff($this->availableCountries, $this->includedCountries);
        sort($this->includedCountries);
        sort($this->excludedCountries);
    }


    public function setDefaultInterval()
    {
        // Set a default value for adRevisionInterval
        $this->adRevisionInterval = 24;
    }

    public function updated($property)
    {
    
        if ($property == 'viewsAmount') {
             
            if ( $this->viewsAmount < PtcAdPackage::where('reward_per_view', $this->packagePrice)->value('minimum_views') ) 
            {
                $this->viewsAmount = PtcAdPackage::where('reward_per_view', $this->packagePrice)->value('minimum_views');
                $this->addError('viewsAmount', 'Min views amount must be at least ' . PtcAdPackage::where('reward_per_view', $this->packagePrice)->value('minimum_views') . ' for the selected package');
            }
            else
            {
                $this->resetErrorBag('viewsAmount');
            }
        }
        else{
            $this->validateOnly($property);
        }
        $this->totalCost = $this->viewsAmount * $this->packagePrice;
    }

    
    public function toggleCountry($country, $box)
    {
        if ($box === 'included') {
            $this->includedCountries = array_diff($this->includedCountries, [$country]);
            $this->excludedCountries[] = $country;
            sort($this->includedCountries);
            sort($this->excludedCountries);
        } else {
            $this->excludedCountries = array_diff($this->excludedCountries, [$country]);
            $this->includedCountries[] = $country;
            sort($this->includedCountries);
            sort($this->excludedCountries);
        }
    }

    public function addPredefinedCountries($countries)
    {
        foreach ($countries as $country) {
            if (!in_array($country, $this->includedCountries)) {
                $this->includedCountries = array_merge($this->includedCountries, $countries);
                $this->excludedCountries = array_diff($this->excludedCountries, $countries);
            }
        }
            sort($this->includedCountries);
            sort($this->excludedCountries);
    }    

    // CountrySelector.php

    public function removeAllCountries()
    {
        $this->excludedCountries = array_merge($this->excludedCountries, $this->includedCountries);
        $this->includedCountries = [];
        sort($this->includedCountries);
        sort($this->excludedCountries);
    }

    public function addAllCountries()
    {
        $this->includedCountries = array_merge($this->includedCountries, $this->excludedCountries);
        $this->excludedCountries = [];
        sort($this->includedCountries);
        sort($this->excludedCountries);
    }

    public function submit()
    {
        $this->validate();
        if ($this->viewsAmount < PtcAdPackage::where('reward_per_view', $this->packagePrice)->value('minimum_views')) {
            $this->viewsAmount = PtcAdPackage::where('reward_per_view', $this->packagePrice)->value('minimum_views');
        }
        $user = User::find(Auth::user()->id);
        $totalAdAmount = $this->packagePrice * $this->viewsAmount;
        if ( ($this->packagePrice * $this->viewsAmount) > User::where('id', Auth::user()->id)->value('deposit_balance') ) 
        {
            $this->addError('lessAmountError', 'Your Advertising Balance is less than the required amount, please deposit first');
        }else
        {
            $this->ptcAd = new PtcAd;        
            $this->countryCount = AvailableCountry::count();
            
            $this->ptcAd->targeted_countries = (sizeOf($this->includedCountries) == $this->countryCount) ? 'worldwide' : json_encode($this->includedCountries);
            $this->ptcAd->unique_id = bin2hex(random_bytes(15));
            $this->ptcAd->title = $this->title;
            $this->ptcAd->description = $this->description;
            $this->ptcAd->url = $this->url;
            $this->ptcAd->seconds = PtcAdPackage::where('reward_per_view', $this->packagePrice)->value('seconds');
            $this->ptcAd->employer_id = Auth::user()->id;
            $this->ptcAd->ad_balance = $this->packagePrice * $this->viewsAmount;
            $this->ptcAd->temp_locked_balance = 0;
            $this->ptcAd->reward_per_view = $this->packagePrice;
            $this->ptcAd->views_needed = $this->viewsAmount;
            $this->ptcAd->views_completed = 0;
            $this->ptcAd->status = 1;
            $this->ptcAd->type = $this->type;  //iframe or window
            $this->ptcAd->revision_interval = $this->adRevisionInterval;
            $this->ptcAd->excluded_countries = json_encode($this->excludedCountries);
            $this->ptcAd->save();
            $user->deductAdvertiserBalance($totalAdAmount);
            return redirect(url('/advertiser/ptc-campaigns-list'))->with('success', 'Your Campaign is Successfully Created and Pending Approval');
            
        }
    } 

    public function render()
    {
        return view('livewire.advertise.ptc.create-ptc');
    }
}
