<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Ajouter une nouvelle tâche au projet :') }} {{ $project->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('tasks.store', $project) }}">
                        @csrf

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Titre de la tâche')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" :value="__('Date de début')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date')" />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" :value="__('Date de fin')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date')" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Priority -->
                            <div>
                                <x-input-label for="priority" :value="__('Priorité')" />
                                <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="low" {{ old('priority', 'medium') == 'low' ? 'selected' : '' }}>Basse</option>
                                    <option value="medium" {{ old('priority', 'medium') == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                    <option value="high" {{ old('priority', 'medium') == 'high' ? 'selected' : '' }}>Haute</option>
                                    <option value="critical" {{ old('priority', 'medium') == 'critical' ? 'selected' : '' }}>Critique</option>
                                </select>
                                <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Statut')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="todo" {{ old('status', 'todo') == 'todo' ? 'selected' : '' }}>À faire (To Do)</option>
                                    <option value="in_progress" {{ old('status', 'todo') == 'in_progress' ? 'selected' : '' }}>En cours (In Progress)</option>
                                    <option value="review" {{ old('status', 'todo') == 'review' ? 'selected' : '' }}>En revue (Review)</option>
                                    <option value="completed" {{ old('status', 'todo') == 'completed' ? 'selected' : '' }}>Terminé (Completed)</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Progress -->
                        <div class="mt-4">
                            <x-input-label for="progress" :value="__('Progression (%)')" />
                            <x-text-input id="progress" class="block mt-1 w-full" type="number" name="progress" :value="old('progress', 0)" min="0" max="100" />
                            <x-input-error :messages="$errors->get('progress')" class="mt-2" />
                        </div>

                        <!-- Parent Task -->
                        <div class="mt-4">
                            <x-input-label for="parent_id" :value="__('Tâche Parente (Optionnel)')" />
                            <select id="parent_id" name="parent_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Aucune --</option>
                                @foreach ($project->tasks as $task)
                                    <option value="{{ $task->id }}" {{ old('parent_id') == $task->id ? 'selected' : '' }}>{{ $task->title }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                        </div>

                        <!-- Prerequisites -->
                        <div class="mt-4">
                            <x-input-label for="prerequisites" :value="__('Tâches Prérequises (Optionnel)')" />
                            <select id="prerequisites" name="prerequisites[]" multiple class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm h-32">
                                @foreach ($project->tasks as $task)
                                    <option value="{{ $task->id }}" {{ in_array($task->id, old('prerequisites', [])) ? 'selected' : '' }}>{{ $task->title }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('prerequisites')" class="mt-2" />
                        </div>

                        <!-- Categories -->
                        <div class="mt-4">
                            <x-input-label for="categories" :value="__('Catégories (Optionnel)')" />
                            <select name="categories[]" id="categories" multiple class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 rounded-md shadow-sm h-24">
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" @selected(in_array($category->id, old('categories', [])))>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="flex items-center justify-end mt-4">
                             <a href="{{ route('projects.show', $project) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Annuler
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Ajouter la tâche') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
