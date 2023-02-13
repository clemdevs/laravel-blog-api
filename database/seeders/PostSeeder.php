<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $post_count = fake()->numberBetween(2, 10);

        //filter admin users (random number of admins)
        $admins = User::all()->filter(fn($user) => $user->isAdmin())->random();

        //only populate posts for admins.
        Post::factory($post_count)->for($admins)->create();
    }
}
