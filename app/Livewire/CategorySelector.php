<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\TaskCategory;
use App\Models\AvailableCountry;
use App\Models\ArabCountry;
use WisdomDiala\Countrypkg\Models\Country;
class CategorySelector extends Component
{ 
    public $parentCategories;
    public $selectedParentCategory;
    public $subCategories;
    public $subCategory;
    public $categoryMinAmount;
    public $selectedSubCategory;
    public $includedCountries = [];
    public $excludedCountries = [];
    public $availableCountries = ['US', 'CA', 'UK', 'AU']; // Dummy list of countries
    public $arabCountries;
    public $europianCountries = ['United Kingdom', 'Australia'];

    public $countriesSelected;

    public function mount()
    {
        $this->arabCountries = ArabCountry::whereNotNull('country_name')->get();
        $this->parentCategories = TaskCategory::whereNull('parent_id')->get();
        $countries = AvailableCountry::pluck('country_name')->toArray();
        $this->includedCountries = $countries;
        $this->availableCountries = array_diff($this->availableCountries, $this->includedCountries);
        sort($this->includedCountries);
        sort($this->excludedCountries);
    }

    public function updatedSelectedParentCategory(){
        $this->subCategory = '';
    }

    public function loadSubCategories()
    {
        $this->subCategories = TaskCategory::where('parent_id', $this->selectedParentCategory)->get();
    }

    public function subCategorySelected(){
        // Fetch the selected subcategory
        $this->subCategory = TaskCategory::with('rewards')->find($this->selectedSubCategory);

        // If subcategory is found, load the associated rewards
        if ($this->subCategory) {
            $this->subCategory->load('rewards');
        }
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
        $errors = session('errors', collect());
    
        return view('livewire.category-selector', compact('errors'));
    }
    
}
