<?php

namespace App\Http\Controllers;

use App\Models\Link;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LinkController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'url' => 'required|url',
            'linkable_type' => ['required', Rule::in(['App\Models\Project', 'App\Models\Task'])],
            'linkable_id' => 'required|integer',
        ]);

        $model = app($validated['linkable_type'])->findOrFail($validated['linkable_id']);

        $this->authorize('update', $model instanceof \App\Models\Task ? $model->project : $model);

        $model->links()->create($validated);

        return back()->with('success', 'Lien ajouté avec succès.');
    }

    public function destroy(Link $link): RedirectResponse
    {
        $model = $link->linkable;
        $this->authorize('update', $model instanceof \App\Models\Task ? $model->project : $model);

        $link->delete();

        return back()->with('success', 'Lien supprimé avec succès.');
    }
}
