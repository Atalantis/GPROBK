<?php

namespace Database\Seeders\Projects;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;

class EmmaReynosoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Create the user
        $user = User::factory()->create([
            'name' => 'Emma REYNOSO',
            'email' => 'emma.reynoso@test.com',
            'role' => 'etudiant',
        ]);

        // 2. Create the project
        $project = Project::factory()->create([
            'student_id' => $user->id,
            'title' => 'Comment réengager la clientèle jeune dans la relation en agence afin de contribuer au développement du portefeuille et du PNB, dans un contexte de digitalisation accrue ?',
        ]);

        // 3. Create the tasks
        $tasks = [
            // Phase 1: Diagnostic & Cadrage
            [
                'title' => '1.1 Prise en main des outils et du template de planning Excel',
                'description' => 'Se familiariser avec les outils de suivi de projet fournis.',
                'start_date' => now()->addDays(0),
                'end_date' => now()->addDays(1),
            ],
            [
                'title' => '1.2 Entretien de cadrage initial avec le tuteur entreprise',
                'description' => 'Définir le périmètre exact du projet et les attentes du tuteur.',
                'start_date' => now()->addDays(1),
                'end_date' => now()->addDays(2),
            ],
            [
                'title' => '2.2 Mener des entretiens avec les conseillers',
                'description' => 'Comprendre leur perception et leurs difficultés avec la clientèle jeune (18-25 ans).',
                'start_date' => now()->addDays(2),
                'end_date' => now()->addDays(5),
            ],
            [
                'title' => '3.2 Formuler une première version de la problématique',
                'description' => 'Rédiger une question claire et précise qui guidera le projet.',
                'start_date' => now()->addDays(5),
                'end_date' => now()->addDays(7),
            ],

            // Phase 2: Recherche & Collecte
            [
                'title' => '4.1 Effectuer la veille réglementaire et concurrentielle',
                'description' => 'Analyser les offres des banques concurrentes pour la clientèle jeune.',
                'start_date' => now()->addDays(7),
                'end_date' => now()->addDays(12),
            ],
            [
                'title' => '5.2 Préparer un guide d\'entretien/questionnaire pour les clients jeunes',
                'description' => 'Élaborer un questionnaire pour comprendre leurs attentes vis-à-vis de l\'agence physique vs. les canaux digitaux.',
                'start_date' => now()->addDays(12),
                'end_date' => now()->addDays(14),
            ],
            [
                'title' => '5.3 Mener les entretiens et collecter les données',
                'description' => 'Interroger un panel de clients jeunes pour recueillir leurs avis.',
                'start_date' => now()->addDays(14),
                'end_date' => now()->addDays(21),
            ],

            // Phase 3: Analyse & Développement
            [
                'title' => '7.1 Segmenter la clientèle jeune',
                'description' => 'Identifier les sous-groupes (étudiants, jeunes actifs, etc.) pour affiner la stratégie.',
                'start_date' => now()->addDays(21),
                'end_date' => now()->addDays(24),
            ],
            [
                'title' => '7.2 Proposer des actions concrètes pour réengager les jeunes en agence',
                'description' => 'Définir des actions spécifiques (événements, offres dédiées, conseil personnalisé, etc.).',
                'start_date' => now()->addDays(24),
                'end_date' => now()->addDays(30),
            ],
            [
                'title' => '8.2 Définir les KPIs pour mesurer le réengagement',
                'description' => 'Choisir les indicateurs clés pour suivre le succès des actions (trafic en agence, PNB du segment, etc.).',
                'start_date' => now()->addDays(30),
                'end_date' => now()->addDays(32),
            ],

            // Phase 4: Rédaction & Finalisation
            [
                'title' => '9.1 Rédiger le dossier professionnel',
                'description' => 'Structurer et rédiger le mémoire en suivant le plan de l\'ESBanque.',
                'start_date' => now()->addDays(32),
                'end_date' => now()->addDays(45),
            ],
            [
                'title' => '10.1 Préparer la soutenance orale',
                'description' => 'Créer le support de présentation et s\'entraîner pour l\'oral.',
                'start_date' => now()->addDays(45),
                'end_date' => now()->addDays(50),
            ],
        ];

        foreach ($tasks as $taskData) {
            Task::factory()->create(array_merge($taskData, ['project_id' => $project->id]));
        }
    }
}