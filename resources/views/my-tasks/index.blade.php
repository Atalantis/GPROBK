<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Mes Tâches') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <!-- Filter and Sort Controls -->
                    <div class="mb-4 flex justify-between items-center">
                        <div>
                            <span class="text-sm font-semibold mr-2">Filtrer par statut:</span>
                            <a href="{{ route('my-tasks.index') }}" class="{{ !request('filter.status') ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500 mr-2">Toutes</a>
                            <a href="{{ route('my-tasks.index', ['filter[status]' => 'todo']) }}" class="{{ request('filter.status') == 'todo' ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500 mr-2">À Faire</a>
                            <a href="{{ route('my-tasks.index', ['filter[status]' => 'in_progress']) }}" class="{{ request('filter.status') == 'in_progress' ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500">En Cours</a>
                        </div>
                        <div>
                            <span class="text-sm font-semibold mr-2">Trier par:</span>
                            <a href="{{ route('my-tasks.index', ['sort' => 'end_date']) }}" class="{{ request('sort') == 'end_date' ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500 mr-2">Échéance</a>
                            <a href="{{ route('my-tasks.index', ['sort' => 'priority']) }}" class="{{ request('sort') == 'priority' ? 'text-blue-600 font-bold' : '' }} text-sm hover:text-blue-500">Priorité</a>
                        </div>
                    </div>

                    <div class="space-y-6">
                        @forelse ($tasks as $projectName => $projectTasks)
                            <div>
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-2">{{ $projectName }}</h3>
                                <div class="space-y-2">
                                    @foreach ($projectTasks as $task)
                                        <a href="{{ route('tasks.edit', $task) }}" class="block bg-gray-50 dark:bg-gray-700 hover:bg-gray-100 dark:hover:bg-gray-600 p-4 rounded-lg shadow">
                                            <div class="flex justify-between">
                                                <p class="font-semibold">{{ $task->title }}</p>
                                                <span class="text-sm font-bold
                                                    @switch($task->priority)
                                                        @case('low') text-gray-500 @break
                                                        @case('medium') text-blue-500 @break
                                                        @case('high') text-yellow-500 @break
                                                        @case('critical') text-red-500 @break
                                                    @endswitch
                                                ">
                                                    {{ ucfirst($task->priority) }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between text-sm text-gray-600 dark:text-gray-400 mt-1">
                                                <span>{{ ucfirst(str_replace('_', ' ', $task->status)) }}</span>
                                                <span @if($task->end_date?->isPast() && $task->status != 'completed') class="text-red-500 font-bold" @endif>
                                                    Échéance: {{ $task->end_date?->format('d M Y') ?? 'N/A' }}
                                                </span>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @empty
                            <p class="text-center text-gray-500">Vous n'avez aucune tâche assignée.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
