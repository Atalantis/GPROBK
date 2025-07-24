<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use App\Notifications\ProjectActivityNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;

class TaskController extends Controller
{
    use AuthorizesRequests;

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project): View
    {
        // Authorize: only the assigned student or a professor can add a task
        $this->authorize('update', $project);

        // Eager load tasks to pass them to the view for parent/dependency selection
        $project->load('tasks');

        return view('tasks.create', compact('project'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,review,completed',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress' => 'required|integer|min:0|max:100',
            'parent_id' => 'nullable|exists:tasks,id',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:tasks,id',
        ]);

        $task = $project->tasks()->create($validated);

        if (isset($validated['prerequisites'])) {
            $task->prerequisites()->sync($validated['prerequisites']);
        }

        return redirect()->route('projects.show', $project)->with('success', 'Tâche ajoutée avec succès.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task): View
    {
        $this->authorize('update', $task->project);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task): View
    {
        $this->authorize('update', $task->project);

        // Eager load project tasks for parent/dependency selection
        $projectTasks = $task->project->tasks()->get();

        return view('tasks.edit', compact('task', 'projectTasks'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task): RedirectResponse
    {
        $this->authorize('update', $task->project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,review,completed',
            'priority' => 'required|in:low,medium,high,critical',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'progress' => 'required|integer|min:0|max:100',
            'parent_id' => 'nullable|exists:tasks,id',
            'prerequisites' => 'nullable|array',
            'prerequisites.*' => 'exists:tasks,id',
        ]);

        $originalStatus = $task->status;
        $task->update($validated);

        if (isset($validated['prerequisites'])) {
            $task->prerequisites()->sync($validated['prerequisites']);
        } else {
            $task->prerequisites()->sync([]);
        }

        // Notify professor if a task is completed by a student
        if ($originalStatus !== 'completed' && $task->status === 'completed' && auth()->user()->role === 'etudiant') {
            $project = $task->project;
            $recipient = $project->student->professeur;

            if ($recipient && !$recipient->isMuted(Project::class, $project->id)) {
                $title = "Tâche terminée";
                $message = "L'étudiant " . auth()->user()->name . " a terminé la tâche : " . $task->title;
                $url = route('tasks.show', $task);

                Notification::send($recipient, new ProjectActivityNotification($title, $message, $url));
            }
        }

        return redirect()->route('projects.show', $task->project)->with('success', 'Tâche mise à jour avec succès.');
    }

    /**
     * Update the status of a task from the Kanban board.
     */
    public function updateStatus(Request $request, Task $task): JsonResponse
    {
        $this->authorize('update', $task->project);

        $validated = $request->validate([
            'status' => 'required|in:todo,in_progress,review,completed',
        ]);

        $originalStatus = $task->status;
        $task->update($validated);

        // Trigger notification logic from the main update method
        if ($originalStatus !== 'completed' && $task->status === 'completed' && auth()->user()->role === 'etudiant') {
            $project = $task->project;
            $recipient = $project->student->professeur;

            if ($recipient && !$recipient->isMuted(Project::class, $project->id)) {
                $title = "Tâche terminée";
                $message = "L'étudiant " . auth()->user()->name . " a terminé la tâche : " . $task->title;
                $url = route('tasks.show', $task);

                Notification::send($recipient, new ProjectActivityNotification($title, $message, $url));
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task): RedirectResponse
    {
        $this->authorize('update', $task->project);
        $project = $task->project;
        $task->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Tâche supprimée avec succès.');
    }
}
