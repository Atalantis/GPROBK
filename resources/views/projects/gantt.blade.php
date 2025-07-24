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
                <div class="p-6 text-gray-900 dark:text-gray-100 relative">
                    <div class="flex space-x-2 mb-4">
                        <x-secondary-button id="gantt-view-day">Jour</x-secondary-button>
                        <x-secondary-button id="gantt-view-week">Semaine</x-secondary-button>
                        <x-secondary-button id="gantt-view-month">Mois</x-secondary-button>
                    </div>
                    <svg id="gantt"></svg>
                    <div id="gantt-tooltip" class="hidden absolute bg-gray-900 text-white text-sm rounded py-1 px-2 pointer-events-none"></div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let gantt;

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

                        document.getElementById('gantt-view-day').addEventListener('click', () => gantt.change_view_mode('Day'));
                        document.getElementById('gantt-view-week').addEventListener('click', () => gantt.change_view_mode('Week'));
                        document.getElementById('gantt-view-month').addEventListener('click', () => gantt.change_view_mode('Month'));

                        // Add a today marker
                        setTimeout(() => {
                            const today_el = document.querySelector('.gantt .today-highlight');
                            if(today_el) {
                                today_el.style.stroke = '#EF4444';
                                today_el.style.strokeWidth = '2px';
                                today_el.style.strokeDasharray = '5, 5';
                            }
                        }, 200);

                        // Tooltip logic
                        const tooltip = document.getElementById('gantt-tooltip');
                        document.querySelectorAll('.gantt .bar-wrapper').forEach(bar => {
                            bar.addEventListener('mouseenter', (e) => {
                                const task = tasks.find(t => t.id == e.target.dataset.id);
                                if(task) {
                                    tooltip.innerHTML = `<strong>${task.name}</strong><br>Progrès: ${task.progress}%`;
                                    tooltip.classList.remove('hidden');
                                }
                            });
                            bar.addEventListener('mousemove', (e) => {
                                tooltip.style.top = (e.pageY + 15) + 'px';
                                tooltip.style.left = (e.pageX + 15) + 'px';
                            });
                            bar.addEventListener('mouseleave', () => {
                                tooltip.classList.add('hidden');
                            });
                        });

                    } else {
                        document.getElementById('gantt').innerHTML = '<p class="text-center text-gray-500">Aucune tâche avec des dates pour afficher le diagramme.</p>';
                    }
                });
        });
    </script>
    @endpush
</x-app-layout>
