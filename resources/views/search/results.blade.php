<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Résultats de recherche pour :') }} "{{ $query }}"
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="space-y-6">
                <!-- Projects -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-bold mb-4">Projets ({{ $projects->count() }})</h3>
                        <ul class="space-y-2">
                            @forelse($projects as $project)
                                <li><a href="{{ route('projects.show', $project) }}" class="text-blue-500 hover:underline">{{ $project->title }}</a></li>
                            @empty
                                <li>Aucun projet trouvé.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>

                <!-- Tasks -->
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 dark:text-gray-100">
                        <h3 class="text-lg font-bold mb-4">Tâches ({{ $tasks->count() }})</h3>
                        <ul class="space-y-2">
                            @forelse($tasks as $task)
                                <li><a href="{{ route('tasks.edit', $task) }}" class="text-blue-500 hover:underline">{{ $task->title }} <span class="text-sm text-gray-500">(Projet: {{ $task->project->title }})</span></a></li>
                            @empty
                                <li>Aucune tâche trouvée.</li>
                            @endforelse
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
