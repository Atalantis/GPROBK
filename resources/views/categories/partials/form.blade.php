<div>
    <!-- Name -->
    <div class="mt-4">
        <x-input-label for="name" :value="__('Nom de la Catégorie')" />
        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $category->name ?? '')" required />
        <x-input-error :messages="$errors->get('name')" class="mt-2" />
    </div>

    <!-- Description -->
    <div class="mt-4">
        <x-input-label for="description" :value="__('Description')" />
        <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm">{{ old('description', $category->description ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('description')" class="mt-2" />
    </div>

    <div class="flex items-center justify-end mt-4">
        <a href="{{ route('categories.index') }}" class="text-sm text-gray-600 hover:underline">Annuler</a>
        <x-primary-button class="ml-4">
            {{ isset($category) ? 'Mettre à jour' : 'Créer' }}
        </x-primary-button>
    </div>
</div>
