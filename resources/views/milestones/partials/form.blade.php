<div>
    <!-- Title -->
    <div class="mt-4">
        <x-input-label for="title" :value="__('Titre du Jalon')" />
        <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $milestone->title ?? '')" required />
        <x-input-error :messages="$errors->get('title')" class="mt-2" />
    </div>

    <!-- Description -->
    <div class="mt-4">
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('description', $milestone->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <!-- Due Date -->
    <div class="mt-4">
        <x-input-label for="due_date" :value="__('Date d\'échéance')" />
        <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', isset($milestone) ? $milestone->due_date?->format('Y-m-d') : '')" />
        <x-input-error :messages="$errors->get('due_date')" class="mt-2" />
    </div>

    <!-- Status -->
    <div class="mt-4">
        <x-input-label for="status" :value="__('Statut')" />
        <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="open" @selected(old('status', $milestone->status ?? 'open') == 'open')>Ouvert</option>
            <option value="completed" @selected(old('status', $milestone->status ?? '') == 'completed')>Terminé</option>
        </select>
        <x-input-error :messages="$errors->get('status')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('projects.show', $project ?? $milestone->project) }}" class="text-sm text-gray-600 hover:underline">Annuler</a>
        <x-primary-button class="ml-4">
            {{ isset($milestone) ? 'Mettre à jour' : 'Créer' }}
        </x-primary-button>
    </div>
</div>