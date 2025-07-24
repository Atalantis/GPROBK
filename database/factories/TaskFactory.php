<?php

namespace Database\Factories;

use App\Models\Project;
use Illuminate\Database\Eloquent\Factories\Factory;

class TaskFactory extends Factory
{
    public function definition(): array
    {
        $startDate = $this->faker->dateTimeBetween('-1 month', '+1 month');
        $endDate = $this->faker->dateTimeBetween($startDate, (clone $startDate)->modify('+2 weeks'));
        $status = $this->faker->randomElement(['todo', 'in_progress', 'review', 'completed']);

        return [
            'project_id' => Project::factory(),
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'status' => $status,
            'priority' => $this->faker->randomElement(['low', 'medium', 'high', 'critical']),
            'start_date' => $startDate,
            'end_date' => $endDate,
            'progress' => $status === 'completed' ? 100 : $this->faker->numberBetween(0, 90),
        ];
    }
}
