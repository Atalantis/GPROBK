<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectActivityNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class ProjectFunctionalityTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that a professor can successfully create a project and a notification is sent.
     */
    public function test_professor_can_create_project_and_student_is_notified(): void
    {
        // 1. Arrange
        Notification::fake(); // Prevent actual notifications from being sent

        $professor = User::factory()->create(['role' => 'professeur']);
        $student = User::factory()->create(['role' => 'etudiant']);

        $projectData = [
            'title' => 'Nouveau projet de test',
            'description' => 'Ceci est une description de test.',
            'student_id' => $student->id,
            'status' => 'active',
        ];

        // 2. Act
        $response = $this->actingAs($professor)->post(route('projects.store'), $projectData);

        // 3. Assert
        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');

        // Assert the project was created in the database
        $this->assertDatabaseHas('projects', [
            'title' => 'Nouveau projet de test',
            'student_id' => $student->id,
        ]);

        // Assert that the student received a notification
        Notification::assertSentTo(
            [$student], ProjectActivityNotification::class
        );
    }

    /**
     * Test that a student cannot access the project creation page.
     */
    public function test_student_cannot_create_project(): void
    {
        // 1. Arrange
        $student = User::factory()->create(['role' => 'etudiant']);

        // 2. Act
        $response = $this->actingAs($student)->get(route('projects.create'));

        // 3. Assert
        $response->assertStatus(403); // Assert Forbidden
    }

    /**
     * Test that a student cannot view a project that does not belong to them.
     */
    public function test_student_cannot_view_another_students_project(): void
    {
        // 1. Arrange
        $student1 = User::factory()->create(['role' => 'etudiant']);
        $student2 = User::factory()->create(['role' => 'etudiant']);

        $project = Project::factory()->create(['student_id' => $student1->id]);

        // 2. Act: Student 2 tries to view Student 1's project
        $response = $this->actingAs($student2)->get(route('projects.show', $project));

        // 3. Assert
        $response->assertStatus(403);
    }

    /**
     * Test that the dashboard is accessible to a professor.
     */
    public function test_dashboard_is_accessible_for_professor(): void
    {
        // 1. Arrange
        $professor = User::factory()->create(['role' => 'professeur']);
        Project::factory()->count(3)->create(); // Create some projects

        // 2. Act
        $response = $this->actingAs($professor)->get(route('dashboard'));

        // 3. Assert
        $response->assertStatus(200);
        $response->assertViewIs('projects.index');
    }
}