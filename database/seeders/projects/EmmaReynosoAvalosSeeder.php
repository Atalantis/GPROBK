<?php

namespace Database\Seeders\projects;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmmaReynosoAvalosSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Emma REYNOSO AVALOS',
            'email' => 'emmareynosoavls@gmail.com',
            'password' => bcrypt('password'),
            'role' => 'etudiant',
        ]);

        $project = Project::factory()->create([
            'student_id' => $user->id,
            'title' => 'Titre du projet à compléter',
        ]);

        $tasks = [
            // Tâches à compléter
        ];

        foreach ($tasks as $taskData) {
            Task::factory()->create(array_merge($taskData, ['project_id' => $project->id]));
        }
    }
}
