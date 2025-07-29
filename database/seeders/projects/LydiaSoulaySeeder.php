<?php

namespace Database\Seeders\Projects;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class LydiaSoulaySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Lydia SOULAY',
            'email' => 'lydia.soulay@test.com',
            'role' => 'etudiant',
        ]);

        $project = Project::factory()->create([
            'student_id' => $user->id,
            'title' => 'Comment mettre en œuvre une stratégie de fidélisation efficace pour les clients de 12 à 25 ans afin de développer durablement le portefeuille jeunes de l\'agence ?',
        ]);

        $tasks = [
            [
                'title' => 'Analyser le taux d\'attrition (churn) sur le segment des 12-25 ans',
                'description' => 'Étudier les raisons et les moments clés des départs des jeunes clients.',
                'start_date' => now()->addDays(0),
                'end_date' => now()->addDays(7),
            ],
            [
                'title' => 'Segmenter la clientèle jeune pour identifier les besoins de fidélisation',
                'description' => 'Différencier les attentes des 12-17 ans, des 18-21 ans et des 22-25 ans.',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(14),
            ],
            [
                'title' => 'Élaborer un programme de fidélité pour les jeunes',
                'description' => 'Concevoir des avantages, des récompenses et une communication spécifique pour renforcer la relation.',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(24),
            ],
            [
                'title' => 'Proposer des actions pour accompagner les moments de vie clés',
                'description' => 'Définir des offres pour le permis de conduire, les études supérieures, le premier emploi, etc.',
                'start_date' => now()->addDays(24),
                'end_date' => now()->addDays(30),
            ],
            [
                'title' => 'Définir les KPIs de la fidélisation jeune',
                'description' => 'Choisir des indicateurs pour mesurer l\'ancienneté, le taux d\'équipement et le PNB par client jeune.',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(35),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::factory()->create(array_merge($taskData, ['project_id' => $project->id]));
        }
    }
}
