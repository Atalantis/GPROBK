<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Diagramme de Gantt : {{ $project->title }}
            </h2>
            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center px-4 py-2 bg-insuractio-secondary border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-purple-700">
                Retour au Projet
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <div class="flex space-x-2 mb-4">
                        <x-secondary-button id="gantt-view-day">Jour</x-secondary-button>
                        <x-secondary-button id="gantt-view-week">Semaine</x-secondary-button>
                        <x-secondary-button id="gantt-view-month">Mois</x-secondary-button>
                    </div>
                    <svg id="gantt"></svg>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let gantt; // Make gantt instance accessible

            fetch('{{ route('projects.gantt.data', $project) }}')
                .then(response => response.json())
                .then(tasks => {
                    if (tasks.length > 0) {
                        gantt = new Gantt("#gantt", tasks, {
                            on_click: (task) => {
                                if (task.id.startsWith('task-')) {
                                    const taskId = task.id.replace('task-', '');
                                    window.location.href = '{{ url('tasks') }}/' + taskId + '/edit';
                                }
                            },
                            language: 'fr'
                        });

                        // Event listeners for view mode buttons
                        document.getElementById('gantt-view-day').addEventListener('click', () => gantt.change_view_mode('Day'));
                        document.getElementById('gantt-view-week').addEventListener('click', () => gantt.change_view_mode('Week'));
                        document.getElementById('gantt-view-month').addEventListener('click', () => gantt.change_view_mode('Month'));

                    } else {
                        document.getElementById('gantt').innerHTML = '<p class="text-center text-gray-500">Aucune tâche avec des dates pour afficher le diagramme.</p>';
                    }
                });
        });
    </script>
    @endpush
</x-app-layout>