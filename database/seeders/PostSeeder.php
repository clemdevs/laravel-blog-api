<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Post;
use App\Models\Tag;
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
        $post_count = fake()->numberBetween(3, 10);
        $category_count = fake()->numberBetween(Category::first()->id, Category::count());
        $tag_count = fake()->numberBetween(Tag::first()->id, Tag::count());

        //filter admin users (random number of admins)
        $admins = User::fetchAdmins()->first();
        //only populate posts for admins.
        Post::factory($post_count)
            ->hasAttached(Category::all()->random($category_count))
            ->hasAttached(Tag::all()->random($tag_count))
            ->for($admins)->create();

    }
}
