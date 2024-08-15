<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => $this->faker->sentence(3),
            'author' => $this->faker->name,
            'isbn' => $this->faker->isbn13,
            'genre' => $this->faker->word,
            'total_copies' => $this->faker->numberBetween(1, 50),
            'available_copies' => $this->faker->numberBetween(0, 50),
        ];
    }
}
