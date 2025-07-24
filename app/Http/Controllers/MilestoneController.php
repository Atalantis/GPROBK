<?php

namespace App\Http\Controllers;

use App\Models\Milestone;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class MilestoneController extends Controller
{
    use AuthorizesRequests;

    public function create(Project $project): View
    {
        $this->authorize('update', $project);
        return view('milestones.create', compact('project'));
    }

    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:open,completed',
        ]);

        $project->milestones()->create($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Jalon créé avec succès.');
    }

    public function edit(Milestone $milestone): View
    {
        $this->authorize('update', $milestone->project);
        return view('milestones.edit', compact('milestone'));
    }

    public function update(Request $request, Milestone $milestone): RedirectResponse
    {
        $this->authorize('update', $milestone->project);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
            'status' => 'required|in:open,completed',
        ]);

        $milestone->update($validated);

        return redirect()->route('projects.show', $milestone->project)->with('success', 'Jalon mis à jour avec succès.');
    }

    public function destroy(Milestone $milestone): RedirectResponse
    {
        $this->authorize('update', $milestone->project);
        $project = $milestone->project;
        $milestone->delete();

        return redirect()->route('projects.show', $project)->with('success', 'Jalon supprimé avec succès.');
    }
}
