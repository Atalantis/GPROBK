<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, (clone $startDate)->modify('+3 months'));

        return [
            'student_id' => User::factory(),
            'title' => $this->faker->bs(),
            'description' => $this->faker->paragraphs(3, true),
            'status' => $this->faker->randomElement(['draft', 'active', 'review', 'completed']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'brief_data' => [
                'problematique' => $this->faker->realText(),
                'objectifs' => $this->faker->realText(),
                'methodologie' => $this->faker->realText(),
            ],
        ];
    }
}
