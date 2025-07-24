<?php

namespace App\Http\Controllers;

use App\Models\CustomFieldDefinition;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomFieldDefinitionController extends Controller
{
    public function index(): View
    {
        $fields = CustomFieldDefinition::orderBy('model_type')->orderBy('name')->get();
        return view('custom-fields.index', compact('fields'));
    }

    public function create(): View
    {
        return view('custom-fields.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,select',
            'model_type' => 'required|in:App\Models\Project,App\Models\Task',
            'options' => 'nullable|string|required_if:type,select',
        ]);

        if ($validated['type'] === 'select' && isset($validated['options'])) {
            $validated['options'] = array_map('trim', explode(',', $validated['options']));
        }

        CustomFieldDefinition::create($validated);

        return redirect()->route('custom-fields.index')->with('success', 'Champ personnalisé créé.');
    }

    public function edit(CustomFieldDefinition $customField): View
    {
        return view('custom-fields.edit', ['field' => $customField]);
    }

    public function update(Request $request, CustomFieldDefinition $customField): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:text,number,date,select',
            'model_type' => 'required|in:App\Models\Project,App\Models\Task',
            'options' => 'nullable|string|required_if:type,select',
        ]);

        if ($validated['type'] === 'select' && isset($validated['options'])) {
            $validated['options'] = array_map('trim', explode(',', $validated['options']));
        } else {
            $validated['options'] = null;
        }

        $customField->update($validated);

        return redirect()->route('custom-fields.index')->with('success', 'Champ personnalisé mis à jour.');
    }

    public function destroy(CustomFieldDefinition $customField): RedirectResponse
    {
        $customField->delete();
        return redirect()->route('custom-fields.index')->with('success', 'Champ personnalisé supprimé.');
    }
}
