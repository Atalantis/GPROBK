<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class LinkFactory extends Factory
{
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(2),
            'url' => $this->faker->url(),
        ];
    }
}
