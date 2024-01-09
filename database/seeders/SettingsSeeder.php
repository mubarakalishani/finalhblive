<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::insert([
            ['name' => 'faucet_claim_time', 'value' => '5'],
            ['name' => 'faucet_claim_amount', 'value' => '0.0005'],
        ]);
    }
}
