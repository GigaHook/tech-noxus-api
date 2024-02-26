<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class MoveImages extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (Post::all() as $post) {
            $post->images()->create([
                'name' => $post->image,
            ]);
        }
    }
}
