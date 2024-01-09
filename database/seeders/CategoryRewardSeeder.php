<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskCategory;
use App\Models\AvailableCountry;
use App\Models\CategoryReward;

class CategoryRewardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $rootCategories = TaskCategory::whereNotNull('parent_id')->get();
        $countries = AvailableCountry::all();

        foreach ($rootCategories as $category) {
            foreach ($countries as $country) {
                CategoryReward::create([
                    'task_category_id' => $category->id,
                    'country_id' => $country->id,
                    'country_name' => $country->country_name,
                    'min_reward_amount' => rand(1, 9) + rand(0, 9999) / 10000 // Adjust the range as needed
                ]);
            }
        }
    }
}

