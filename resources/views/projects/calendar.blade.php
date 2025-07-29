<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Calendrier du Projet : {{ $project->title }}
            </h2>
            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-insuractio-secondary text-white rounded-md">
                Retour au Projet
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div id="calendar" data-events-url="{{ route('projects.calendar.data', $project) }}"></div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
