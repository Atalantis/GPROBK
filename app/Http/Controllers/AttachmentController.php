<?php

namespace App\Http\Controllers;

use App\Models\Attachment;
use App\Models\Project;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AttachmentController extends Controller
{
    use AuthorizesRequests;

    public function store(Request $request, Project $project): RedirectResponse
    {
        $this->authorize('update', $project);

        $request->validate([
            'attachment' => 'required|file|max:10240', // 10MB Max
        ]);

        $file = $request->file('attachment');
        $path = $file->store('attachments', 'public');

        $project->attachments()->create([
            'user_id' => $request->user()->id,
            'original_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Fichier envoyé avec succès.');
    }

    public function destroy(Attachment $attachment): RedirectResponse
    {
        $this->authorize('update', $attachment->project);

        // Delete the file from storage
        Storage::disk('public')->delete($attachment->file_path);

        // Delete the record from the database
        $attachment->delete();

        return redirect()->route('projects.show', $attachment->project)->with('success', 'Fichier supprimé avec succès.');
    }
}
