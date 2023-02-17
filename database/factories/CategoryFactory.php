<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category_names = [
            'Hello',
            'Business',
            'Technology',
            'News',
            'Vehicles',
            'Animals',
            'Sci-fi',
            'Entertainment',
            'Social'
        ];

        return [
            'name' => fake()->unique(true)->randomElement($category_names),
            'description' => fake()->paragraph(rand(2,4))
        ];
    }
}
