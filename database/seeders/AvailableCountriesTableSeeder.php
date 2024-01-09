<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AvailableCountry;
use WisdomDiala\Countrypkg\Models\Country;
use Illuminate\Support\Facades\DB;


class AvailableCountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countriesData = Country::select('name', 'short_name')->get();

        // Transform the data and seed the available_countries table
        foreach ($countriesData as $countryData) {
            AvailableCountry::create([
                'country_name' => $countryData->name,
                'country_code' => $countryData->short_name,
            ]);
        }
    }
}
