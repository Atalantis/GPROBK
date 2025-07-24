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
                    <div id="gantt"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/frappe-gantt/dist/frappe-gantt.css">

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            fetch('{{ route('projects.gantt.data', $project) }}')
                .then(response => response.json())
                .then(tasks => {
                    if (tasks.length > 0) {
                        var gantt = new Gantt("#gantt", tasks, {
                            on_click: (task) => {
                                window.location.href = '{{ url('/tasks') }}/' + task.id;
                            },
                            on_date_change: (task, start, end) => {
                                // Here you could add logic to update the task via an API call
                                console.log(`Task ${task.id} dates changed to ${start} - ${end}`);
                            },
                            on_progress_change: (task, progress) => {
                                // Here you could add logic to update the task via an API call
                                console.log(`Task ${task.id} progress changed to ${progress}%`);
                            },
                            language: 'fr' // Set language to French
                        });
                    } else {
                        document.getElementById('gantt').innerHTML = '<p>Aucune tâche avec des dates de début et de fin pour afficher le diagramme de Gantt.</p>';
                    }
                });
        });
    </script>
    @endpush
</x-app-layout>
