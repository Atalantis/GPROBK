<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\QueryBuilder\QueryBuilder;

class MyTasksController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();

        $tasksQuery = Task::query()->with('project');

        if ($user->role === 'etudiant') {
            $projectIds = $user->projects()->pluck('id');
            $tasksQuery->whereIn('project_id', $projectIds);
        }
        // For professors, we show all tasks from all projects

        $tasks = QueryBuilder::for($tasksQuery)
            ->allowedFilters(['status'])
            ->allowedSorts(['end_date', 'priority'])
            ->get()
            ->groupBy('project.title');

        return view('my-tasks.index', compact('tasks'));
    }
}
