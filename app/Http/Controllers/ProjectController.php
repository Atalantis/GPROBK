<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\CustomFieldDefinition;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\ProjectActivityNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller
{
    use AuthorizesRequests;

    public function index(): View
    {
        $user = auth()->user();
        
        $baseQuery = $user->role === 'professeur'
            ? Project::query()
            : $user->projects();

        // First, get all projects for global stats before filtering
        $allProjects = (clone $baseQuery)->get();
        $stats = [
            'total' => $allProjects->count(),
            'draft' => $allProjects->where('status', 'draft')->count(),
            'active' => $allProjects->where('status', 'active')->count(),
            'review' => $allProjects->where('status', 'review')->count(),
            'completed' => $allProjects->where('status', 'completed')->count(),
            'overdue_tasks' => Task::whereIn('project_id', $allProjects->pluck('id'))
                ->where('status', '!=', 'completed')
                ->where('end_date', '<', now())
                ->count(),
        ];

        // Then, get the filtered/sorted projects for the list view
        $projects = QueryBuilder::for($baseQuery)
            ->allowedFilters(['status'])
            ->with('student', 'tasks')
            ->latest()
            ->get();

        $chartData = [
            'labels' => ['Brouillon', 'Actif', 'En Revue', 'Terminé'],
            'data' => [
                $stats['draft'],
                $stats['active'],
                $stats['review'],
                $stats['completed'],
            ],
        ];

        return view('projects.index', compact('projects', 'stats', 'chartData'));
    }

    public function kanban(Project $project): View
    {
        $this->authorize('view', $project);
        $project->load('tasks.categories', 'tasks.comments', 'tasks.children');
        return view('projects.kanban', compact('project'));
    }

    public function gantt(Project $project): View
    {
        $this->authorize('view', $project);
        return view('projects.gantt', compact('project'));
    }

    public function ganttData(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $tasks = $project->tasks()->with('prerequisites')->get();
        $milestones = $project->milestones()->whereNotNull('due_date')->get();

        $formattedTasks = $tasks->map(function ($task) {
            return [
                'id' => 'task-' . $task->id,
                'name' => $task->title,
                'start' => $task->start_date?->format('Y-m-d'),
                'end' => $task->end_date?->format('Y-m-d'),
                'progress' => $task->progress,
                'dependencies' => $task->prerequisites->map(fn($p) => 'task-' . $p->id)->implode(','),
                'custom_class' => 'bar-' . str_replace('_', '-', $task->status)
            ];
        });

        $formattedMilestones = $milestones->map(function ($milestone) {
            return [
                'id' => 'milestone-' . $milestone->id,
                'name' => 'Jalon: ' . $milestone->title,
                'start' => $milestone->due_date->format('Y-m-d'),
                'end' => $milestone->due_date->format('Y-m-d'),
                'progress' => 100,
                'dependencies' => '',
                'custom_class' => 'bar-milestone' . ($milestone->status === 'completed' ? ' bar-milestone-completed' : '')
            ];
        });

        return response()->json($formattedTasks->merge($formattedMilestones));
    }

    public function calendar(Project $project): View
    {
        $this->authorize('view', $project);
        return view('projects.calendar', compact('project'));
    }

    public function calendarData(Project $project): JsonResponse
    {
        $this->authorize('view', $project);

        $tasks = $project->tasks()->whereNotNull('end_date')->get(['id', 'title', 'end_date as date', 'status']);
        $milestones = $project->milestones()->whereNotNull('due_date')->get(['id', 'title', 'due_date as date', 'status', 'project_id']);

        $events = $tasks->map(function ($task) {
            return [
                'title' => 'Tâche: ' . $task->title,
                'start' => $task->date->format('Y-m-d'),
                'url' => route('tasks.edit', $task),
                'color' => $task->status_color,
            ];
        })->merge($milestones->map(function ($milestone) {
            return [
                'title' => 'Jalon: ' . $milestone->title,
                'start' => $milestone->date->format('Y-m-d'),
                'url' => route('projects.show', $milestone->project_id),
                'color' => $milestone->status === 'completed' ? '#10B981' : '#EF4444',
            ];
        }));

        return response()->json($events);
    }

    public function create(): View
    {
        $this->authorize('create', Project::class);

        $students = User::where('role', 'etudiant')->orderBy('name')->get();
        $customFields = CustomFieldDefinition::where('model_type', Project::class)->get();
        $categories = Category::orderBy('name')->get();

        return view('projects.create', compact('students', 'customFields', 'categories'));
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
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        DB::transaction(function () use ($validated) {
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

            if (isset($validated['categories'])) {
                $project->categories()->sync($validated['categories']);
            }

            // Notify the student
            $student = User::find($validated['student_id']);
            if ($student) {
                Notification::send($student, new ProjectActivityNotification("Nouveau projet assigné", "Le projet '{$project->title}' vous a été assigné.", route('projects.show', $project)));
            }
        });

        return redirect()->route('dashboard')->with('success', 'Projet créé avec succès et étudiant notifié.');
    }

    public function show(Project $project): View
    {
        $this->authorize('view', $project);
        $project->load(['tasks.children', 'tasks' => fn($q) => $q->whereNull('parent_id'), 'comments.user', 'attachments.user', 'customFields.definition', 'categories', 'milestones']);
        return view('projects.show', compact('project'));
    }

    public function edit(Project $project): View
    {
        $this->authorize('update', $project);
        $students = User::where('role', 'etudiant')->orderBy('name')->get();
        $customFields = CustomFieldDefinition::where('model_type', Project::class)->get();
        $categories = Category::orderBy('name')->get();
        $project->load('customFields', 'categories');
        return view('projects.edit', compact('project', 'students', 'customFields', 'categories'));
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
            'categories' => 'nullable|array',
            'categories.*' => 'exists:categories,id',
        ]);

        DB::transaction(function () use ($project, $validated) {
            $originalStatus = $project->status;
            $project->update($validated);

            if (isset($validated['custom_fields'])) {
                foreach ($validated['custom_fields'] as $fieldId => $value) {
                    if (!is_null($value)) {
                        $project->customFields()->updateOrCreate(['custom_field_definition_id' => $fieldId], ['value' => $value]);
                    }
                }
            }

            if (isset($validated['categories'])) {
                $project->categories()->sync($validated['categories']);
            } else {
                $project->categories()->sync([]);
            }

            if ($originalStatus !== $project->status) {
                $user = auth()->user();
                $recipient = $user->role === 'etudiant' ? $project->student->professeur : $project->student;
                if ($recipient && !$recipient->isMuted(Project::class, $project->id)) {
                    Notification::send($recipient, new ProjectActivityNotification("Statut du projet mis à jour", "Le statut du projet '{$project->title}' est passé à '{$project->status}'.", route('projects.show', $project)));
                }
            }
        });

        return redirect()->route('dashboard')->with('success', 'Projet mis à jour avec succès.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $this->authorize('delete', $project);
        $project->delete();
        return redirect()->route('dashboard')->with('success', 'Projet supprimé avec succès.');
    }

    public function brief(Project $project): View
    {
        $this->authorize('update', $project);
        return view('projects.brief', compact('project'));
    }

    public function updateBrief(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);
        $validated = $request->validate([
            'problematique' => 'nullable|string',
            'objectifs' => 'nullable|string',
            'methodologie' => 'nullable|string',
            'planning_previsionnel' => 'nullable|string',
            'livrables_attendus' => 'nullable|string',
            'bibliographie' => 'nullable|string',
        ]);
        $project->update(['brief_data' => $validated]);
        return redirect()->route('projects.show', $project)->with('success', 'Brief du projet mis à jour.');
    }
}