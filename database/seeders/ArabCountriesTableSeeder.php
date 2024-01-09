<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
class ArabCountriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $arabCountries = [
            ['country_code' => 'SA', 'country_name' => 'Saudi Arabia'],
            ['country_code' => 'AE', 'country_name' => 'United Arab Emirates'],
            // Add more countries as needed
        ];

        // Insert data into the table
        DB::table('arab_countries')->insert($arabCountries);
    }
}
