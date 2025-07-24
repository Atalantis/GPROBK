<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Notifications\ProjectActivityNotification;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class CommentController extends Controller
{
    use AuthorizesRequests;

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('view', $project);

        $validated = $request->validate([
            'body' => 'required|string',
        ]);

        $comment = $project->comments()->create([
            'user_id' => $request->user()->id,
            'body' => $validated['body'],
        ]);

        // Notify the other party
        $user = $request->user();
        $recipient = $user->role === 'etudiant' ? $project->student->professeur : $project->student;

        if ($recipient && !$recipient->isMuted(Project::class, $project->id)) {
            $title = "Nouveau commentaire sur le projet : " . $project->title;
            $message = $user->name . " a écrit : " . str()->limit($comment->body, 50);
            $url = route('projects.show', $project);

            Notification::send($recipient, new ProjectActivityNotification($title, $message, $url));
        }

        return redirect()->route('projects.show', $project)->with('success', 'Commentaire ajouté.');
    }
}
