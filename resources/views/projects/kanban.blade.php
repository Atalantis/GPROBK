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

    <div class="py-12" x-data="{ showTaskModal: false, taskModalContent: '' }">
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
                                        <div @click="showTaskModal = true; fetch('{{ route('tasks.show.api', $task) }}').then(r => r.text()).then(html => taskModalContent = html)"
                                             class="bg-white dark:bg-gray-800 p-3 rounded-md shadow cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700"
                                             data-task-id="{{ $task->id }}">
                                            <div class="flex justify-between items-start">
                                                <h4 class="font-semibold text-sm">{{ $task->title }}</h4>
                                                <span class="text-xs font-bold
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
                                            <div class="flex flex-wrap gap-1 mt-2">
                                                @foreach($task->categories as $category)
                                                    <span class="px-2 py-1 text-xs font-semibold bg-gray-200 text-gray-800 rounded-full">{{ $category->name }}</span>
                                                @endforeach
                                            </div>
                                            <div class="flex justify-between items-center mt-3 text-xs text-gray-500 dark:text-gray-400">
                                                <span @if($task->end_date?->isPast() && $task->status != 'completed') class="text-red-500 font-bold" @endif>
                                                    {{ $task->end_date?->format('d M') ?? 'Pas de date' }}
                                                </span>
                                                <div class="flex items-center space-x-2">
                                                    @if($task->children->isNotEmpty())
                                                        <span title="{{ $task->children->count() }} sous-tâche(s)">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                        </span>
                                                    @endif
                                                    @if($task->comments->isNotEmpty())
                                                        <span title="{{ $task->comments->count() }} commentaire(s)">
                                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
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

        <!-- Task Detail Modal -->
        <x-modal name="task-modal" x-show="showTaskModal" max-width="2xl">
            <div x-html="taskModalContent">
                <div class="p-6 text-center">
                    <p>Chargement des détails de la tâche...</p>
                </div>
            </div>
        </x-modal>
    </div>
</x-app-layout>
