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
        $category_count = fake()->numberBetween(1, 6);
        $tag_count = fake()->numberBetween(1, 10);

        //fetch admin users (random number of admins)
        $random_admin = User::fetchAdmins()->get()->random();
        //only populate posts for admins.
        Post::factory($post_count)
            ->hasCategories(Category::factory($category_count))
            ->hasTags(Tag::factory($tag_count))
            ->for($random_admin)->create();

    }
}
