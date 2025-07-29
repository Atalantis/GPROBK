<?php

namespace Database\Seeders\Projects;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class VanessaCostaSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Vanessa COSTA',
            'email' => 'vanessa.costa@test.com',
            'role' => 'etudiant',
        ]);

        $project = Project::factory()->create([
            'student_id' => $user->id,
            'title' => 'Comment développer une stratégie efficace pour optimiser la vente de crédits à la consommation afin d\'accroître le PNB, tout en respectant la conformité et la satisfaction client ?',
        ]);

        $tasks = [
            [
                'title' => 'Analyser le portefeuille de crédits à la consommation existant',
                'description' => 'Étudier la performance, les taux de défaut et la rentabilité des crédits conso actuels.',
                'start_date' => now()->addDays(0),
                'end_date' => now()->addDays(7),
            ],
            [
                'title' => 'Effectuer une veille sur les offres de crédit conso concurrentes',
                'description' => 'Comparer les taux, les conditions et les stratégies marketing des concurrents.',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(14),
            ],
            [
                'title' => 'Identifier les segments de clientèle à plus fort potentiel pour le crédit conso',
                'description' => 'Analyser les données clients pour cibler les profils les plus susceptibles de souscrire un crédit.',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(21),
            ],
            [
                'title' => 'Proposer des actions pour améliorer le parcours de souscription',
                'description' => 'Identifier les points de friction dans le processus de demande de crédit et proposer des améliorations.',
                'start_date' => now()->addDays(21),
                'end_date' => now()->addDays(28),
            ],
            [
                'title' => 'Définir des indicateurs de performance pour la stratégie crédit conso',
                'description' => 'Choisir des KPIs pour suivre le volume de production, le PNB généré, le taux de risque et la satisfaction client.',
                'start_date' => now()->addDays(28),
                'end_date' => now()->addDays(32),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::factory()->create(array_merge($taskData, ['project_id' => $project->id]));
        }
    }
}
