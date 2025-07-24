<?php

namespace App\Http\Controllers;

use App\Mail\ProjectAssigned;
use App\Models\CustomFieldDefinition;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\ProjectActivityNotification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    // ... (index, kanban, gantt, ganttData methods remain the same)

    public function create(): View
    {
        $this->authorize('create', Project::class);

        $students = User::where('role', 'etudiant')->orderBy('name')->get();
        $customFields = CustomFieldDefinition::where('model_type', Project::class)->get();

        return view('projects.create', compact('students', 'customFields'));
    }

    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,active,review,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'custom_fields' => 'nullable|array',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $project = Project::create($validated);

            if (isset($validated['custom_fields'])) {
                foreach ($validated['custom_fields'] as $fieldId => $value) {
                    if (!is_null($value)) {
                        $project->customFields()->create([
                            'custom_field_definition_id' => $fieldId,
                            'value' => $value,
                        ]);
                    }
                }
            }

            // Notify the student
            $student = User::find($validated['student_id']);
            if ($student) {
                $title = "Nouveau projet assigné";
                $message = "Le projet '{$project->title}' vous a été assigné.";
                $url = route('projects.show', $project);
                Notification::send($student, new ProjectActivityNotification($title, $message, $url));
            }
        });

        return redirect()->route('dashboard')->with('success', 'Projet créé avec succès et étudiant notifié.');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load(['tasks', 'comments.user', 'attachments.user', 'customFields.definition']);

        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        $students = User::where('role', 'etudiant')->orderBy('name')->get();
        $customFields = CustomFieldDefinition::where('model_type', Project::class)->get();
        $project->load('customFields');

        return view('projects.edit', compact('project', 'students', 'customFields'));
    }

    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,active,review,completed',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'custom_fields' => 'nullable|array',
        ]);

        DB::transaction(function () use ($project, $validated) {
            $originalStatus = $project->status;
            $project->update($validated);

            if (isset($validated['custom_fields'])) {
                foreach ($validated['custom_fields'] as $fieldId => $value) {
                    if (!is_null($value)) {
                        $project->customFields()->updateOrCreate(
                            ['custom_field_definition_id' => $fieldId],
                            ['value' => $value]
                        );
                    }
                }
            }

            // Notify about status change
            if ($originalStatus !== $project->status) {
                $user = auth()->user();
                $recipient = $user->role === 'etudiant' ? $project->student->professeur : $project->student;

                if ($recipient && !$recipient->isMuted(Project::class, $project->id)) {
                    $title = "Statut du projet mis à jour";
                    $message = "Le statut du projet '{$project->title}' est passé à '{$project->status}'.";
                    $url = route('projects.show', $project);
                    Notification::send($recipient, new ProjectActivityNotification($title, $message, $url));
                }
            }
        });

        return redirect()->route('dashboard')->with('success', 'Projet mis à jour avec succès.');
    }
    
    // ... (destroy, brief, updateBrief methods remain the same)
}

