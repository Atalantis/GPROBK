<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __invoke(Request $request): View
    {
        $query = $request->input('query');

        $projects = Project::search($query)->get();
        $tasks = Task::search($query)->get();

        // For students, filter results to only their projects/tasks
        if (auth()->user()->role === 'etudiant') {
            $projectIds = auth()->user()->projects()->pluck('id');
            $projects = $projects->whereIn('id', $projectIds);
            $tasks = $tasks->whereIn('project_id', $projectIds);
        }

        return view('search.results', compact('projects', 'tasks', 'query'));
    }
}
