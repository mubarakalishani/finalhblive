<?php

namespace App\Admin\Actions\TaskCategory;

use Illuminate\Http\Request;
use OpenAdmin\Admin\Actions\Action;
use App\Models\CategoryReward;
use App\Models\AvailableCountry;
use App\Models\Tier1Country;
use App\Models\Tier2Counry;
use App\Models\TaskCategory;
use App\Models\Setting;

class AddCountryRewards extends Action
{
    protected $selector = '.add-country-rewards';

    public function handle(Request $request)
    {
        $keys = explode(',', $request->input('_key'));

        // store each selected task in tasks
        $categories = TaskCategory::whereIn('id', $keys)->whereNotNull('parent_id')->get();
        $availableCountries = AvailableCountry::all();
        $tier1Countries = Tier1Country::all();
        $tier2Countries = Tier2Counry::all();

        foreach ($categories as $category) {
            foreach ($availableCountries as $availableCountry) {
                $foundInTier1Countries = Tier1Country::where('country_name', $availableCountry->country_name)->exists();
                $foundInTier2Countries = Tier2Counry::where('country_name', $availableCountry->country_name)->exists();

                if ($foundInTier1Countries) {
                    $rewardToAdd = $category->min_reward * Setting::where('name', 'tier1_countries_multiplier')->value('value');
                }
                elseif ($foundInTier2Countries) {
                    $rewardToAdd = $category->min_reward * Setting::where('name', 'tier2_countries_multiplier')->value('value');
                }
                else {
                    $rewardToAdd = $category->min_reward;
                }

                $alreadyFound = CategoryReward::where('task_category_id', $category->id)->where('country_name', $availableCountry->country_name)->exists(); //if the country reward for the task already found
                if ($alreadyFound) {
                    $foundRecord = CategoryReward::where('task_category_id', $category->id)->where('country_name', $availableCountry->country_name )->first();
                    $foundRecord->update(['min_reward_amount' => $rewardToAdd]);
                } else {
                    CategoryReward::create([
                        'task_category_id' => $category->id,
                        'country_id' => $availableCountry->id,
                        'country_name' => $availableCountry->country_name,
                        'min_reward_amount' => $rewardToAdd,
                    ]);
                }
                
                

                
            }
        }
        return $this->response()->success('Selected Category Rewards created or updated Successfully')->refresh();
    }

    public function html()
    {
        return "<a class='add-country-rewards btn btn-sm btn-success show-on-rows-selected d-none me-1'>
        <i class='icon-wallet'></i> Add Country Rewards</i>
    </a>";
    }
}