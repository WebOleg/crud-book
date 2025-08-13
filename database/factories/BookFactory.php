<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'description' => fake()->optional()->paragraph(),
            'publication_date' => fake()->date(),
            'image_path' => null,
        ];
    }
}
