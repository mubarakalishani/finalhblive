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
        $signup = TaskCategory::create(['name' => 'Sign Up']);
        $facebook = TaskCategory::create(['name' => 'Facebook']);
        $twitter = TaskCategory::create(['name' => 'Twitter']);
        $telegram = TaskCategory::create(['name' => 'Telegram']);
        $instagram = TaskCategory::create(['name' => 'Instagram']);
        $discord = TaskCategory::create(['name' => 'Discord']);
        $reddit = TaskCategory::create(['name' => 'Reddit']);
        $tiktok = TaskCategory::create(['name' => 'Tiktok']);
        $youtube = TaskCategory::create(['name' => 'Youtube']);
        $mobileApps = TaskCategory::create(['name' => 'Mobile Application']);
        $seo = TaskCategory::create(['name' => 'SEO, Content Promotion']);
        $other = TaskCategory::create(['name' => 'Other']);

        // Sign up sub-subcategories
        $signup->children()->create(['name' => 'Email Submit Only']);
        $signup->children()->create(['name' => 'Simple Sign Up']);
        $signup->children()->create(['name' => 'Complex Sign Up']);
        //facebook sub categories
        $facebook->children()->create(['name' => 'Like a Post']);
        $facebook->children()->create(['name' => 'Share a post']);
        $facebook->children()->create(['name' => 'Join a Group']);
        $facebook->children()->create(['name' => 'Comment on a post']);
        $facebook->children()->create(['name' => 'Follow Page/Profile']);
        $facebook->children()->create(['name' => 'Post on wall']);
        $facebook->children()->create(['name' => 'Like + comment + share/follow']);

        //twitter
        $twitter->children()->create(['name' => 'Retweet / quote tweet']);
        $twitter->children()->create(['name' => 'Like a tweet']);
        $twitter->children()->create(['name' => 'Comment']);
        $twitter->children()->create(['name' => 'Follow']);
        $twitter->children()->create(['name' => 'like + follow + retweet/comment']);
        
        //Telegram
        $telegram->children()->create(['name' => 'Join a bot']);
        $telegram->children()->create(['name' => 'Join a Group']);
        $telegram->children()->create(['name' => 'Join a group/bot + tasks inside']);

        //instagram
        $instagram->children()->create(['name' => 'Instagram follow']);
        $instagram->children()->create(['name' => 'Instagram Like']);
        $instagram->children()->create(['name' => 'Instagram comment']);
        $instagram->children()->create(['name' => 'Instagram share a post']);
        $instagram->children()->create(['name' => 'Instagram Like + follow + comment']);

        //Discord
        $discord->children()->create(['name' => 'Join a channel']);
        $discord->children()->create(['name' => 'Join a server']);
        $discord->children()->create(['name' => 'Join server + tasks inside']);

        //Reddit
        $reddit->children()->create(['name' => 'Upvote a post']);
        $reddit->children()->create(['name' => 'Search + upvote']);
        $reddit->children()->create(['name' => 'Comment']);
        $reddit->children()->create(['name' => 'Upvote + comment']);
        $reddit->children()->create(['name' => 'Join a community']);

        //tiktok
        $tiktok->children()->create(['name' => 'Like/follow/comment']);
        $tiktok->children()->create(['name' => 'Watch a video']);
        $tiktok->children()->create(['name' => 'Share a video']);
        $tiktok->children()->create(['name' => 'Comment + like + watch + share']);

        //Youtube
        $youtube->children()->create(['name' => 'watch a video(upto 10min)']);
        $youtube->children()->create(['name' => 'Watch a video (10min+)']);
        $youtube->children()->create(['name' => 'subscribe channel']);
        $youtube->children()->create(['name' => 'Like video']);
        $youtube->children()->create(['name' => 'Share a video']);
        $youtube->children()->create(['name' => 'Comment']);
        $youtube->children()->create(['name' => 'Like + subscribe + comment']);

        //Social Media
        $socialMedia->children()->create(['name' => 'Social media tasks + engage']);

        //seo
        $seo->children()->create(['name' => 'SEO + Visit Pages (upto 5)']);
        $seo->children()->create(['name' => 'SEO + Visit Pages (upto 10)']);
        $seo->children()->create(['name' => 'SEO + Visit Pages (upto 15)']);

        //Mobile Applications
        $mobileApps->children()->create(['name' => 'Download Only']);
        $mobileApps->children()->create(['name' => 'Download + install + signup']);
        $mobileApps->children()->create(['name' => 'Download + install + review']);

        $mobileApps->children()->create(['name' => 'Describe the task and set reasonable price']);
        
    }
}
