<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\PtcAdPackage;

class PtcAdPackageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PtcAdPackage::insert([
            ['name' => 'package 1', 'reward_per_view' => 0.0001, 'minimum_views' => 1000, 'seconds' => 5],
            ['name' => 'package 2', 'reward_per_view' => 0.0002, 'minimum_views' => 1000, 'seconds' => 15],
            ['name' => 'package 3', 'reward_per_view' => 0.0003, 'minimum_views' => 1000, 'seconds' => 30],
            ['name' => 'package 4', 'reward_per_view' => 0.0004, 'minimum_views' => 1000, 'seconds' => 45],
            ['name' => 'package 4', 'reward_per_view' => 0.0005, 'minimum_views' => 1000, 'seconds' => 60],
        ]);
    }
}
