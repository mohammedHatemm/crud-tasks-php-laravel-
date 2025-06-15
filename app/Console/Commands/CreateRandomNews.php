<?php

namespace App\Console\Commands;

use App\Models\News;
use App\Models\User;
use App\Models\Category;
use Illuminate\Console\Command;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewsNotification; // ←
use Illuminate\Support\Facades\Notification; // ←

class CreateRandomNews extends Command
{
    protected $signature = 'news:create-random';
    protected $description = 'Create random news every minute';

    public function handle()
    {
        try {
            Log::info('Starting random news creation...');
            $faker = Faker::create('en_US');

            $user = User::where('role', 'admin')->inRandomOrder()->first();

            if (!$user) {
                Log::error('No admin user found!');
                return Command::FAILURE;
            }

            $categories = Category::inRandomOrder()->limit(rand(1, 3))->get();

            $news = News::create([
                'name' => $faker->realText(50),
                'content' => $faker->realText(500),
                'user_id' => $user->id,
            ]);

            $news->categories()->attach($categories->pluck('id')->toArray());
            $admins = User::where('role', 'admin')->get();
            Notification::send($admins, new NewsNotification($news));

            Log::info("News created: {$news->name}");
            return Command::SUCCESS;
        } catch (\Exception $e) {
            Log::error('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
