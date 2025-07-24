<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Modifier la tâche :') }} {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <form method="POST" action="{{ route('tasks.update', $task) }}">
                        @csrf
                        @method('PATCH')

                        <!-- Title -->
                        <div>
                            <x-input-label for="title" :value="__('Titre de la tâche')" />
                            <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $task->title)" required autofocus />
                            <x-input-error :messages="$errors->get('title')" class="mt-2" />
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <x-input-label for="description" :value="__('Description')" />
                            <textarea id="description" name="description" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description', $task->description) }}</textarea>
                            <x-input-error :messages="$errors->get('description')" class="mt-2" />
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Start Date -->
                            <div>
                                <x-input-label for="start_date" :value="__('Date de début')" />
                                <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', $task->start_date?->format('Y-m-d'))" />
                                <x-input-error :messages="$errors->get('start_date')" class="mt-2" />
                            </div>

                            <!-- End Date -->
                            <div>
                                <x-input-label for="end_date" :value="__('Date de fin')" />
                                <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', $task->end_date?->format('Y-m-d'))" />
                                <x-input-error :messages="$errors->get('end_date')" class="mt-2" />
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <!-- Priority -->
                            <div>
                                <x-input-label for="priority" :value="__('Priorité')" />
                                <select id="priority" name="priority" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="low" {{ old('priority', $task->priority) == 'low' ? 'selected' : '' }}>Basse</option>
                                    <option value="medium" {{ old('priority', $task->priority) == 'medium' ? 'selected' : '' }}>Moyenne</option>
                                    <option value="high" {{ old('priority', $task->priority) == 'high' ? 'selected' : '' }}>Haute</option>
                                    <option value="critical" {{ old('priority', $task->priority) == 'critical' ? 'selected' : '' }}>Critique</option>
                                </select>
                                <x-input-error :messages="$errors->get('priority')" class="mt-2" />
                            </div>

                            <!-- Status -->
                            <div>
                                <x-input-label for="status" :value="__('Statut')" />
                                <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="todo" {{ old('status', $task->status) == 'todo' ? 'selected' : '' }}>À faire (To Do)</option>
                                    <option value="in_progress" {{ old('status', $task->status) == 'in_progress' ? 'selected' : '' }}>En cours (In Progress)</option>
                                    <option value="review" {{ old('status', $task->status) == 'review' ? 'selected' : '' }}>En revue (Review)</option>
                                    <option value="completed" {{ old('status', $task->status) == 'completed' ? 'selected' : '' }}>Terminé (Completed)</option>
                                </select>
                                <x-input-error :messages="$errors->get('status')" class="mt-2" />
                            </div>
                        </div>

                        <!-- Progress -->
                        <div class="mt-4">
                            <x-input-label for="progress" :value="__('Progression (%)')" />
                            <x-text-input id="progress" class="block mt-1 w-full" type="number" name="progress" :value="old('progress', $task->progress)" min="0" max="100" />
                            <x-input-error :messages="$errors->get('progress')" class="mt-2" />
                        </div>

                        <!-- Parent Task -->
                        <div class="mt-4">
                            <x-input-label for="parent_id" :value="__('Tâche Parente (Optionnel)')" />
                            <select id="parent_id" name="parent_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">-- Aucune --</option>
                                @foreach ($projectTasks as $projectTask)
                                    @if ($projectTask->id !== $task->id) {{-- Prevent a task from being its own parent --}}
                                        <option value="{{ $projectTask->id }}" {{ old('parent_id', $task->parent_id) == $projectTask->id ? 'selected' : '' }}>{{ $projectTask->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('parent_id')" class="mt-2" />
                        </div>

                        <!-- Prerequisites -->
                        <div class="mt-4">
                            <x-input-label for="prerequisites" :value="__('Tâches Prérequises (Optionnel)')" />
                            <select id="prerequisites" name="prerequisites[]" multiple class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm h-32">
                                @php
                                    $prerequisiteIds = old('prerequisites', $task->prerequisites->pluck('id')->toArray());
                                @endphp
                                @foreach ($projectTasks as $projectTask)
                                     @if ($projectTask->id !== $task->id) {{-- Prevent a task from depending on itself --}}
                                        <option value="{{ $projectTask->id }}" {{ in_array($projectTask->id, $prerequisiteIds) ? 'selected' : '' }}>{{ $projectTask->title }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('prerequisites')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">
                             <a href="{{ route('projects.show', $task->project) }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                                Annuler
                            </a>
                            <x-primary-button class="ml-4">
                                {{ __('Mettre à jour la tâche') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

