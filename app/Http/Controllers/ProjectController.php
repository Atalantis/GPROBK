<?php

namespace App\Http\Controllers;

use App\Mail\ProjectAssigned;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $user = \Auth::user();
        $projects = collect();

        if ($user->role === 'professeur') {
            // For professors, get all projects and eager load the student data
            $projects = Project::with('student')->latest()->get();
        } else {
            // For students, get only their own projects
            $projects = $user->projects()->with('student')->latest()->get();
        }

        return view('projects.index', compact('projects'));
    }

    /**
     * Display the project tasks in a Kanban board.
     */
    public function kanban(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load('tasks');

        return view('projects.kanban', compact('project'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $this->authorize('create', Project::class);

        $students = User::where('role', 'etudiant')->orderBy('name')->get();

        return view('projects.create', compact('students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->authorize('create', Project::class);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,active,review,completed',
        ]);

        $project = Project::create($validated);

        // Send email to the student
        $student = User::find($validated['student_id']);
        Mail::to($student)->send(new ProjectAssigned($project));

        return redirect()->route('dashboard')->with('success', 'Projet créé avec succès et étudiant notifié.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project): View
    {
        $this->authorize('view', $project);

        $project->load(['tasks', 'comments.user', 'attachments.user']);

        return view('projects.show', compact('project'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project): View
    {
        $this->authorize('update', $project);

        $students = User::where('role', 'etudiant')->orderBy('name')->get();

        return view('projects.edit', compact('project', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'student_id' => 'required|exists:users,id',
            'status' => 'required|in:draft,active,review,completed',
        ]);

        $project->update($validated);

        return redirect()->route('dashboard')->with('success', 'Projet mis à jour avec succès.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project): RedirectResponse
    {
        // Only professors can delete projects.
        abort_if(Auth::user()->role !== 'professeur', 403, 'Action non autorisée.');

        $project->delete();

        return redirect()->route('dashboard')->with('success', 'Projet supprimé avec succès.');
    }

    /**
     * Show the form for editing the project brief.
     */
    public function brief(Project $project): View
    {
        $this->authorize('update', $project);

        return view('projects.brief', compact('project'));
    }

    /**
     * Update the project's brief data.
     */
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
