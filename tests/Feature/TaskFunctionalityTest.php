<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a user can create a task with parent and prerequisite dependencies.
     */
    public function test_user_can_create_task_with_dependencies(): void
    {
        // 1. Arrange
        $user = User::factory()->create();
        $project = Project::factory()->create(['student_id' => $user->id]);
        $parentTask = Task::factory()->create(['project_id' => $project->id]);
        $prerequisiteTask = Task::factory()->create(['project_id' => $project->id]);

        $taskData = [
            'title' => 'Tâche dépendante',
            'description' => 'Description de la tâche.',
            'status' => 'todo',
            'priority' => 'medium',
            'progress' => 0,
            'parent_id' => $parentTask->id,
            'prerequisites' => [$prerequisiteTask->id],
        ];

        // 2. Act
        $response = $this->actingAs($user)->post(route('tasks.store', $project), $taskData);

        // 3. Assert
        $response->assertRedirect(route('projects.show', $project));
        $response->assertSessionHas('success');

        // Assert the task was created with the correct parent
        $this->assertDatabaseHas('tasks', [
            'title' => 'Tâche dépendante',
            'parent_id' => $parentTask->id,
        ]);

        // Get the newly created task
        $newTask = Task::where('title', 'Tâche dépendante')->first();

        // Assert the prerequisite relationship was created
        $this->assertDatabaseHas('task_dependencies', [
            'dependent_task_id' => $newTask->id,
            'prerequisite_task_id' => $prerequisiteTask->id,
        ]);
    }
}