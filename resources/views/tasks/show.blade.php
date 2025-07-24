<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Détail de la Tâche : {{ $task->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Informations sur la Tâche</h3>
                    <div class="mt-4 space-y-2 text-sm text-gray-600 dark:text-gray-400">
                        <p><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Titre:</strong> <span>{{ $task->title }}</span></p>
                        <p class="flex items-center"><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Priorité:</strong>
                            <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @switch($task->priority)
                                    @case('low') bg-gray-200 text-gray-800 @break
                                    @case('medium') bg-blue-200 text-blue-800 @break
                                    @case('high') bg-yellow-200 text-yellow-800 @break
                                    @case('critical') bg-red-200 text-red-800 @break
                                @endswitch
                            ">
                                {{ ucfirst($task->priority) }}
                            </span>
                        </p>
                        <p class="flex items-center"><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Statut:</strong>
                             <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                @switch($task->status)
                                    @case('todo') bg-gray-200 text-gray-800 @break
                                    @case('in_progress') bg-blue-200 text-blue-800 @break
                                    @case('review') bg-yellow-200 text-yellow-800 @break
                                    @case('completed') bg-green-200 text-green-800 @break
                                @endswitch
                            ">
                                {{ ucfirst(str_replace('_', ' ', $task->status)) }}
                            </span>
                        </p>
                        <p><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Créée le:</strong> <span>{{ $task->created_at->format('d/m/Y H:i') }}</span></p>
                        <p><strong class="w-32 inline-block font-semibold text-gray-900 dark:text-gray-100">Mise à jour le:</strong> <span>{{ $task->updated_at->format('d/m/Y H:i') }}</span></p>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <div class="w-full">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Description</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                        {!! nl2br(e($task->description)) ?: 'Aucune description fournie.' !!}
                    </p>
                </div>
            </div>

             <div class="flex justify-start">
                <a href="{{ route('projects.show', $task->project) }}" class="inline-flex items-center px-4 py-2 bg-gray-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-500 active:bg-gray-700 focus:outline-none focus:border-gray-700 focus:ring ring-gray-300 disabled:opacity-25 transition ease-in-out duration-150">
                    Retour au Projet
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
