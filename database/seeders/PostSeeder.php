<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 0; $i < 25; $i++) {
            Post::create([
                'title' => fake()->words(asText: true),
                'text'  => fake()->realTextBetween(200, 600),
                'image' => fake()->image('public/storage', fullPath: false),
            ]);
        }

        Artisan::call('cache:clear');
    }
}
