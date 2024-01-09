<?php

namespace App\Livewire;
use WisdomDiala\Countrypkg\Models\Country;
use App\Models\AvailableCountry;
use App\Models\AsianCountry;
use App\Models\Tier1Country;
use App\Models\MiddleEastCountry;
use App\Models\AfricanCountry;
use App\Models\ArabCountry;
use App\Models\MuslimCountry;
use Livewire\Component;
class CountrySelector extends Component
{
    public $includedCountries = [];
    public $excludedCountries = [];
    public $availableCountries = ['US', 'CA', 'UK', 'AU']; // Dummy list of countries
    public $arabCountries = ['US', 'CA', 'UK', 'AU'];
    public $europianCountries = ['United Kingdom', 'Australia'];
    
    // Add some initial countries to the included box for testing
    public function mount()
    {
        $countries = Country::pluck('name')->toArray();
        $this->includedCountries = $countries;
        $this->availableCountries = array_diff($this->availableCountries, $this->includedCountries);
        sort($this->includedCountries);
        sort($this->excludedCountries);
    }
    
    // Method to move a country between boxes
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
  
     
    public function render()
    {
        return view('livewire.country-selector');
    }
}
