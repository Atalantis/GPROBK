<div>
    <!-- Name -->
    <div class="mt-4">
        <x-input-label for="name" :value="__('Nom du Champ')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $field->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Model Type -->
    <div class="mt-4">
        <x-input-label for="model_type" :value="__('Appliquer à')" />
        <select id="model_type" name="model_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="App\Models\Project" @selected(old('model_type', $field->model_type ?? '') == 'App\Models\Project')>Projet</option>
            <option value="App\Models\Task" @selected(old('model_type', $field->model_type ?? '') == 'App\Models\Task')>Tâche</option>
        </select>
        <x-input-error :messages="$errors->get('model_type')" class="mt-2" />
    </div>

    <!-- Type -->
    <div class="mt-4">
        <x-input-label for="type" :value="__('Type de Champ')" />
        <select id="type" name="type" x-model="type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">
            <option value="text">Texte</option>
            <option value="number">Nombre</option>
            <option value="date">Date</option>
            <option value="select">Liste de choix</option>
        </select>
        <x-input-error :messages="$errors->get('type')" class="mt-2" />
    </div>

    <!-- Options (for select type) -->
    <div class="mt-4" x-show="type === 'select'">
        <x-input-label for="options" :value="__('Options (séparées par des virgules)')" />
        <x-text-input id="options" class="block mt-1 w-full" type="text" name="options" :value="old('options', isset($field) && is_array($field->options) ? implode(', ', $field->options) : '')" />
        <x-input-error :messages="$errors->get('options')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('custom-fields.index') }}" class="text-sm text-gray-600 hover:underline">Annuler</a>
        <x-primary-button class="ml-4">
            {{ isset($field) ? 'Mettre à jour' : 'Créer' }}
        </x-primary-button>
    </div>
</div>
