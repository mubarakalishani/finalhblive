<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskCategory;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $socialMedia = TaskCategory::create(['name' => 'Social Media']);
        $technology = TaskCategory::create(['name' => 'Technology']);
        $travel = TaskCategory::create(['name' => 'Travel']);

        // Create subcategories
        $twitter = $socialMedia->children()->create(['name' => 'Twitter']);
        $facebook = $socialMedia->children()->create(['name' => 'Facebook']);
        $programming = $technology->children()->create(['name' => 'Programming']);
        $gadgets = $technology->children()->create(['name' => 'Gadgets']);
        $europe = $travel->children()->create(['name' => 'Europe']);
        $asia = $travel->children()->create(['name' => 'Asia']);

        // Create sub-subcategories
        $twitterLike = $twitter->children()->create(['name' => 'Twitter Like']);
        $twitterFollow = $twitter->children()->create(['name' => 'Twitter Follow']);
        $programmingLanguage = $programming->children()->create(['name' => 'Programming Language']);
        $gadgetReview = $gadgets->children()->create(['name' => 'Gadget Review']);
    }
}
