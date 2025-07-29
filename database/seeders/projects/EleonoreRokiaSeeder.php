<?php

namespace Database\Seeders\Projects;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class EleonoreRokiaSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::factory()->create([
            'name' => 'Eléonore ROKIA',
            'email' => 'eleonore.rokia@test.com',
            'role' => 'etudiant',
        ]);

        $project = Project::factory()->create([
            'student_id' => $user->id,
            'title' => 'Comment exploiter les données clients relatives aux enfants à charge pour cibler les foyers à potentiel et développer la prospection jeunes 12-25 ans afin d\'accroître le PNB ?',
        ]);

        $tasks = [
            [
                'title' => 'Analyser la structure des données clients existantes',
                'description' => 'Vérifier la disponibilité et la fiabilité des données sur les enfants à charge dans le CRM.',
                'start_date' => now()->addDays(0),
                'end_date' => now()->addDays(5),
            ],
            [
                'title' => 'Définir les critères d\'un \'foyer à potentiel\'',
                'description' => 'Établir des règles pour identifier les familles les plus susceptibles d\'être intéressées par des offres pour leurs enfants.',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(10),
            ],
            [
                'title' => 'Construire une segmentation de la cible jeune (12-25 ans)',
                'description' => 'Définir des sous-groupes (collégiens, lycéens, étudiants, jeunes actifs) et leurs besoins spécifiques.',
                'start_date' => now()->addDays(10),
                'end_date' => now()->addDays(15),
            ],
            [
                'title' => 'Élaborer des offres commerciales adaptées à chaque segment jeune',
                'description' => 'Créer des packages de produits et services bancaires pertinents pour chaque étape de la vie des jeunes.',
                'start_date' => now()->addDays(15),
                'end_date' => now()->addDays(25),
            ],
            [
                'title' => 'Définir un plan de contact respectueux du RGPD',
                'description' => 'Concevoir une approche pour contacter les parents (les clients actuels) afin de leur présenter les offres pour leurs enfants.',
                'start_date' => now()->addDays(25),
                'end_date' => now()->addDays(30),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::factory()->create(array_merge($taskData, ['project_id' => $project->id]));
        }
    }
}
