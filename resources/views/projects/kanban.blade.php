<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Kanban: {{ $project->title }}
            </h2>
            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700">
                Retour à la Vue Détaillée
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-full mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @php
                        $statuses = [
                            'todo' => 'À Faire',
                            'in_progress' => 'En Cours',
                            'review' => 'En Revue',
                            'completed' => 'Terminé',
                        ];
                    @endphp

                    <div class="flex space-x-4 overflow-x-auto pb-4" id="kanban-board">
                        @foreach ($statuses as $status => $statusName)
                            <div class="w-80 bg-gray-100 dark:bg-gray-700 rounded-lg shadow-md flex-shrink-0">
                                <h3 class="font-bold p-3 text-gray-700 dark:text-gray-200 border-b border-gray-200 dark:border-gray-600">{{ $statusName }}</h3>
                                <div class="p-2 space-y-2 kanban-column" data-status="{{ $status }}">
                                    @forelse ($project->tasks->where('status', $status) as $task)
                                        <div class="bg-white dark:bg-gray-800 p-3 rounded-md shadow cursor-grab" data-task-id="{{ $task->id }}">
                                            <h4 class="font-semibold text-sm">{{ $task->title }}</h4>
                                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1">Priorité:
                                                <span class="font-bold
                                                    @switch($task->priority)
                                                        @case('low') text-gray-500 @break
                                                        @case('medium') text-blue-500 @break
                                                        @case('high') text-yellow-500 @break
                                                        @case('critical') text-red-500 @break
                                                    @endswitch
                                                ">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </p>
                                        </div>
                                    @empty
                                        <div class="p-3 text-xs text-gray-500 text-center">Aucune tâche ici.</div>
                                    @endforelse
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
