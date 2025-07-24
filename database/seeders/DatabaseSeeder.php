<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Link;
use App\Models\Milestone;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Create a professor
        $professeur = User::factory()->create([
            'name' => 'Professeur Test',
            'email' => 'prof@test.com',
            'role' => 'professeur',
        ]);

        // 2. Create some global categories
        $categories = collect([
            ['name' => 'Recherche & Développement', 'slug' => 'recherche-developpement'],
            ['name' => 'Analyse de Données', 'slug' => 'analyse-donnees'],
            ['name' => 'Conception UI/UX', 'slug' => 'conception-ui-ux'],
            ['name' => 'Marketing Digital', 'slug' => 'marketing-digital'],
            ['name' => 'Rédaction Technique', 'slug' => 'redaction-technique'],
        ])->map(fn ($cat) => Category::factory()->create($cat));

        // 3. Create 3 comprehensive test suites
        for ($i = 1; $i <= 3; $i++) {
            $this->createTestProject($professeur, $categories, $i);
        }
    }

    private function createTestProject(User $professeur, $categories, int $index): void
    {
        // Create a student for the project
        $etudiant = User::factory()->create([
            'name' => "Étudiant Test {$index}",
            'email' => "etudiant{$index}@test.com",
            'role' => 'etudiant',
        ]);

        // Create the main project
        $project = Project::factory()->create([
            'student_id' => $etudiant->id,
            'title' => "Projet de Test {$index} - " . fake()->catchPhrase(),
        ]);

        // Attach categories, links, and milestones to the project
        $project->categories()->attach($categories->random(2)->pluck('id'));
        Link::factory()->count(2)->create(['linkable_id' => $project->id, 'linkable_type' => Project::class]);
        Milestone::factory()->count(3)->create(['project_id' => $project->id]);

        // Create a set of tasks for the project
        $tasks = Task::factory()->count(8)->create(['project_id' => $project->id]);

        // Create sub-tasks for some of the main tasks
        foreach ($tasks->random(3) as $parentTask) {
            Task::factory()->count(2)->create([
                'project_id' => $project->id,
                'parent_id' => $parentTask->id,
                'start_date' => $parentTask->start_date,
                'end_date' => $parentTask->end_date,
            ]);
        }

        // Create dependencies between tasks
        $allTasks = $project->tasks;
        foreach ($allTasks as $task) {
            if ($allTasks->where('id', '!=', $task->id)->isNotEmpty()) {
                $prerequisite = $allTasks->where('id', '!=', $task->id)->random();
                // Ensure the prerequisite ends before the dependent task starts
                if ($task->start_date && $prerequisite->end_date && $task->start_date > $prerequisite->end_date) {
                     $task->prerequisites()->attach($prerequisite->id);
                }
            }
        }

        // Create comments between the professor and the student
        Comment::factory()->count(5)->create([
            'project_id' => $project->id,
            'user_id' => $professeur->id,
        ]);
        Comment::factory()->count(5)->create([
            'project_id' => $project->id,
            'user_id' => $etudiant->id,
        ]);
    }
}
