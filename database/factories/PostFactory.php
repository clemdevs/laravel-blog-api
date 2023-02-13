<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        //TODO: shout have dummy data for posts
        return [
            'title' => fake()->unique()->name(),
            'body' => fake()->text(50),
            'image_url' => fake()->imageUrl(),
            'user_id' => User::factory()->create()->id
        ];
    }
}
