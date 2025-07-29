<?php

namespace Database\Seeders\projects;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class JeanPhilippeCunySeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Jean-Philippe CUNY',
            'email' => 'jp.cuny@test.com',
            'role' => 'etudiant',
        ]);

        $project = Project::factory()->create([
            'student_id' => $user->id,
            'title' => 'Quelles stratégies pour dynamiser l\'activité commerciale et augmenter le PNB, en conciliant relation client et transition numérique ?',
        ]);

        $tasks = [
            [
                'title' => 'Analyser la performance commerciale actuelle de l\'agence',
                'description' => 'Étudier les sources du PNB, la performance par produit et l\'évolution de la fréquentation physique.',
                'start_date' => now()->addDays(0),
                'end_date' => now()->addDays(7),
            ],
            [
                'title' => 'Sonder les clients sur leurs préférences de canaux (physique vs. digital)',
                'description' => 'Créer et diffuser un questionnaire pour comprendre les attentes des différentes segments de clientèle.',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(14),
            ],
            [
                'title' => 'Proposer des stratégies pour valoriser les rendez-vous en agence',
                'description' => 'Définir des actions à forte valeur ajoutée pour justifier le déplacement en agence (expertise, conseil complexe).',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(21),
            ],
            [
                'title' => 'Définir des actions pour dynamiser les ventes sur les canaux numériques',
                'description' => 'Identifier des parcours de souscription en ligne à optimiser ou à promouvoir.',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(21),
            ],
            [
                'title' => 'Définir les KPIs de la performance commerciale omnicanale',
                'description' => 'Choisir des indicateurs pour suivre le PNB, la satisfaction client et l\'utilisation des différents canaux.',
                'start_date' => now()->addDays(21),
                'end_date' => now()->addDays(25),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::factory()->create(array_merge($taskData, ['project_id' => $project->id]));
        }
    }
}
