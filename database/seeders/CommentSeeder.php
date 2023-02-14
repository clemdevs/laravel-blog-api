<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CommentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $comments_count = fake()->numberBetween(1, 10);
        $post_count = fake()->numberBetween(Post::first()->id, Post::count());

        Comment::factory($comments_count)->hasPosts($post_count)->create();
    }
}
