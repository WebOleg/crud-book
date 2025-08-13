<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class AuthorFactory extends Factory
{
    public function definition(): array
    {
        return [
            'last_name' => fake()->lastName(),
            'first_name' => fake()->firstName(),
            'middle_name' => fake()->optional()->firstName(),
        ];
    }
}
