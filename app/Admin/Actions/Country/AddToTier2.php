<?php

namespace App\Admin\Actions\Country;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\AvailableCountry;
use App\Models\Tier1Country;
use App\Models\Tier2Counry;


class AddToTier2 extends Action
{
    protected $selector = '.add-to-tier2';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected task in tasks
        $countries = AvailableCountry::whereIn('id', $keys)->get();
        $tier1Countries = Tier1Country::all();
        $tier2Countries = Tier2Counry::all();

        foreach ($countries as $country) {
            $foundInTier1Countries = Tier1Country::where('country_name', $country->country_name)->exists();
            $foundInTier2Countries = Tier2Counry::where('country_name', $country->country_name)->exists();

            if (!$foundInTier1Countries && !$foundInTier2Countries) {
                Tier2Counry::create([
                    'country_code' => $country->country_code,
                    'country_name' => $country->country_name,
                ]);
            }
        }

        return $this->response()->success('Selected Country(s) Added to Tier2 list Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='add-to-tier2 btn btn-sm btn-primary show-on-rows-selected d-none me-1 mt-1 mb-1'>Add to Tier2</a>";
    }
}